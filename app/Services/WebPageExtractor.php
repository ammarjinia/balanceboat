<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Fetches a center's own public website and reduces it to plain text an LLM can read, for the
 * "Fill my form" import on the create-retreat wizard (see RetreatImportService and
 * App\Http\Controllers\Center\CenterRetreatImportController).
 *
 * Two things this deliberately does NOT do:
 *
 *  - It does not render JavaScript. A site whose content only exists after hydration extracts to
 *    almost nothing, which is why the import card offers a "paste your brochure instead" textarea
 *    as a first-class path rather than an error state.
 *  - It does not crawl. It fetches the URL it was given, reads that page's own anchors, and follows
 *    at most MAX_PAGES - 1 of them, ranked by how likely they are to describe this retreat. A center
 *    site with one page per retreat and a center site listing eight retreats on one page both work
 *    without the caller having to know which it is dealing with.
 *
 * Every outbound request is guarded against server-side request forgery: scheme must be http(s),
 * every resolved IP must be publicly routable, and each redirect hop is re-checked before it is
 * followed (a public hostname redirecting to 169.254.169.254 is the whole attack).
 */
class WebPageExtractor
{
    private const MAX_PAGES = 6;
    private const MAX_BYTES_PER_PAGE = 400000;
    private const MAX_CHARS_PER_PAGE = 14000;
    private const MAX_REDIRECTS = 3;
    private const MAX_IMAGES = 24;
    private const TIMEOUT = 8;

    /**
     * Wall-clock ceiling for the whole extraction, in seconds. Per-page timeouts alone are not
     * enough: six pages at eight seconds each is 48 seconds in one request, which exceeds the
     * 30-second max_execution_time typical of shared hosting and turns into a 500 with an HTML
     * body. We stop collecting sub-pages once this is spent and return what we have, because a
     * partial read still fills most of the form.
     */
    private const TIME_BUDGET = 20;

    /** Slugs that usually hold the content the wizard needs. */
    private const USEFUL_SLUGS = [
        'retreat', 'program', 'programme', 'package', 'course', 'schedule', 'itinerary',
        'about', 'accommodation', 'accommodations', 'room', 'stay', 'food', 'dining', 'meal',
        'price', 'pricing', 'rate', 'booking', 'faq', 'contact', 'location', 'how-to-reach',
        'getting-here', 'inclusion', 'yoga', 'ayurveda', 'wellness', 'detox', 'treatment',
    ];

    /** Filename fragments that mean "chrome", not "photo of the retreat". */
    private const IMAGE_NOISE = [
        'logo', 'icon', 'favicon', 'sprite', 'placeholder', 'avatar', 'badge', 'flag',
        'arrow', 'bullet', 'spinner', 'loader', 'pixel', 'blank', 'spacer', 'whatsapp',
    ];

    /**
     * @return array{
     *   pages: array<int, array{url:string, title:string, text:string}>,
     *   images: string[],
     *   start_url: string,
     *   notes: string[],
     * }
     */
    public function extract(string $startUrl, string $retreatName = ''): array
    {
        $startUrl = $this->normalizeUrl($startUrl);
        $notes = [];
        $deadline = microtime(true) + self::TIME_BUDGET;

        $seed = $this->fetch($startUrl);
        if ($seed === null) {
            throw new RuntimeException(
                "We couldn't open that web address. Check it opens in your browser, then try again — "
                . "or paste your retreat description in the box instead."
            );
        }

        $pages = [];
        $images = [];

        $seedParsed = $this->parseHtml($seed['body'], $seed['url']);
        $pages[] = ['url' => $seed['url'], 'title' => $seedParsed['title'], 'text' => $seedParsed['text']];
        $images = array_merge($images, $seedParsed['images']);

        $candidates = $this->rankLinks($seedParsed['links'], $seed['url'], $retreatName);

        foreach ($candidates as $link) {
            if (count($pages) >= self::MAX_PAGES) {
                break;
            }
            // Leave room for one more page's timeout rather than starting a fetch we cannot finish.
            if (microtime(true) + self::TIMEOUT > $deadline) {
                $notes[] = 'Your site was slow to respond, so we read the main page and stopped there.';
                break;
            }
            $page = $this->fetch($link);
            if ($page === null) {
                continue;
            }
            $parsed = $this->parseHtml($page['body'], $page['url']);
            if (Str::length($parsed['text']) < 200) {
                continue; // a nav-only or image-only page adds nothing but tokens
            }
            $pages[] = ['url' => $page['url'], 'title' => $parsed['title'], 'text' => $parsed['text']];
            $images = array_merge($images, $parsed['images']);
        }

        $totalText = array_sum(array_map(fn ($p) => Str::length($p['text']), $pages));
        if ($totalText < 400) {
            $notes[] = 'There was very little readable text on that site. If it loads its content with '
                . 'JavaScript, paste your retreat description instead for a much better result.';
        }

        return [
            'pages' => $pages,
            'images' => array_slice(array_values(array_unique($images)), 0, self::MAX_IMAGES),
            'start_url' => $seed['url'],
            'notes' => $notes,
        ];
    }

    // ------------------------------------------------------------------ fetching

    /**
     * Fetches one URL, following redirects by hand so every hop can be re-validated.
     *
     * @return array{url:string, body:string}|null null for anything unreachable, non-HTML, or
     *                                              pointing somewhere we refuse to go.
     */
    private function fetch(string $url): ?array
    {
        $hops = 0;

        while (true) {
            try {
                $this->assertSafeUrl($url);
            } catch (RuntimeException $e) {
                if ($hops === 0) {
                    throw $e; // the address the user typed — they deserve the reason
                }
                return null; // a redirect we simply decline to follow
            }

            try {
                $response = Http::withOptions(['allow_redirects' => false])
                    ->timeout(self::TIMEOUT)
                    ->withHeaders([
                        'User-Agent' => 'BalanceBoatImporter/1.0 (+https://balanceboat.com; retreat listing import)',
                        'Accept' => 'text/html,application/xhtml+xml',
                    ])
                    ->get($url);
            } catch (\Throwable $e) {
                return null;
            }

            if ($response->redirect()) {
                $location = (string) $response->header('Location');
                if ($location === '' || ++$hops > self::MAX_REDIRECTS) {
                    return null;
                }
                $url = $this->absolutize($url, $location);
                continue;
            }

            if (!$response->successful()) {
                return null;
            }

            $contentType = strtolower((string) $response->header('Content-Type'));
            if ($contentType !== '' && !Str::contains($contentType, ['text/html', 'application/xhtml'])) {
                return null;
            }

            $body = $response->body();
            if (strlen($body) > self::MAX_BYTES_PER_PAGE) {
                $body = substr($body, 0, self::MAX_BYTES_PER_PAGE);
            }

            return ['url' => $url, 'body' => $body];
        }
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            throw new RuntimeException('Please enter your website address.');
        }
        if (!preg_match('~^https?://~i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }
        return $url;
    }

    /**
     * Rejects anything that is not a plain public http(s) address. Credentials in the URL are
     * refused too — they are a classic way to make a blocked host look like an allowed one.
     */
    private function assertSafeUrl(string $url): void
    {
        $parts = parse_url($url);
        if ($parts === false || empty($parts['host'])) {
            throw new RuntimeException('That does not look like a valid web address.');
        }

        $scheme = strtolower($parts['scheme'] ?? '');
        if (!in_array($scheme, ['http', 'https'], true)) {
            throw new RuntimeException('Only http and https web addresses can be imported.');
        }
        if (isset($parts['user']) || isset($parts['pass'])) {
            throw new RuntimeException('Web addresses containing a username or password cannot be imported.');
        }

        $host = $parts['host'];
        $ips = filter_var(trim($host, '[]'), FILTER_VALIDATE_IP)
            ? [trim($host, '[]')]
            : (gethostbynamel($host) ?: []);

        if (empty($ips)) {
            throw new RuntimeException("We couldn't find a website at that address.");
        }

        foreach ($ips as $ip) {
            if (!$this->isPublicIp($ip)) {
                throw new RuntimeException('That address points to a private network and cannot be imported.');
            }
        }
    }

    private function isPublicIp(string $ip): bool
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return false;
        }
        // Carrier-grade NAT (100.64.0.0/10) is not covered by the filter flags above.
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $long = ip2long($ip);
            if ($long !== false && ($long & 0xFFC00000) === 0x64400000) {
                return false;
            }
        }
        return true;
    }

    private function absolutize(string $base, string $ref): string
    {
        $ref = trim($ref);
        if ($ref === '' || Str::startsWith($ref, ['#', 'mailto:', 'tel:', 'javascript:', 'data:'])) {
            return '';
        }
        if (preg_match('~^https?://~i', $ref)) {
            return $ref;
        }
        if (Str::startsWith($ref, '//')) {
            return (parse_url($base, PHP_URL_SCHEME) ?: 'https') . ':' . $ref;
        }

        $parts = parse_url($base);
        if ($parts === false || empty($parts['host'])) {
            return '';
        }
        $root = ($parts['scheme'] ?? 'https') . '://' . $parts['host'] . (isset($parts['port']) ? ':' . $parts['port'] : '');

        if (Str::startsWith($ref, '/')) {
            return $root . $ref;
        }

        $dir = rtrim(dirname($parts['path'] ?? '/'), '/');
        return $root . $dir . '/' . $ref;
    }

    // ------------------------------------------------------------------ parsing

    /**
     * @return array{title:string, text:string, links: array<int, array{url:string, text:string}>, images:string[]}
     */
    private function parseHtml(string $html, string $pageUrl): array
    {
        $previous = libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        // The XML declaration is the reliable way to stop DOMDocument assuming ISO-8859-1 and
        // mangling every accented character in a European center's copy.
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new \DOMXPath($dom);

        $title = '';
        $titleNode = $xpath->query('//title')->item(0);
        if ($titleNode) {
            $title = $this->collapse($titleNode->textContent);
        }

        $links = [];
        foreach ($xpath->query('//a[@href]') as $a) {
            /** @var \DOMElement $a */
            $abs = $this->absolutize($pageUrl, $a->getAttribute('href'));
            if ($abs === '') {
                continue;
            }
            $links[] = ['url' => $abs, 'text' => $this->collapse($a->textContent)];
        }

        $images = [];
        foreach ($xpath->query('//img') as $img) {
            /** @var \DOMElement $img */
            $src = $img->getAttribute('src') ?: $img->getAttribute('data-src') ?: $img->getAttribute('data-lazy-src');
            $abs = $this->absolutize($pageUrl, $src);
            if ($abs === '' || !preg_match('~\.(jpe?g|png|webp|avif)(\?|$)~i', $abs)) {
                continue;
            }
            if (Str::contains(Str::lower($abs), self::IMAGE_NOISE)) {
                continue;
            }
            $images[] = $abs;
        }

        // Strip everything that is navigation, styling or behavior before reading the text, so the
        // model sees the page's actual prose instead of the same menu repeated six times.
        foreach ($xpath->query('//script|//style|//noscript|//svg|//iframe|//form|//nav|//header|//footer|//aside') as $node) {
            $node->parentNode?->removeChild($node);
        }

        // textContent concatenates with no separator, so a card reading "Ayurveda Retreat" above a
        // badge reading "Gentle" comes out as "Ayurveda RetreatGentle" — two facts fused into one
        // nonsense token before the model ever sees them. Put a newline in front of every block
        // element first so the structure the page had survives into the text.
        foreach ($xpath->query('//p|//div|//li|//tr|//br|//h1|//h2|//h3|//h4|//h5|//h6|//section|//article|//td|//dt|//dd|//blockquote') as $block) {
            $block->parentNode?->insertBefore($dom->createTextNode("\n"), $block);
        }

        $bodyNode = $xpath->query('//body')->item(0) ?? $dom->documentElement;
        $text = $bodyNode ? $this->collapseBlocks($bodyNode->textContent) : '';

        if (Str::length($text) > self::MAX_CHARS_PER_PAGE) {
            $text = Str::limit($text, self::MAX_CHARS_PER_PAGE, '');
        }

        return ['title' => $title, 'text' => $text, 'links' => $links, 'images' => $images];
    }

    private function collapse(string $text): string
    {
        return trim(preg_replace('/\s+/u', ' ', html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');
    }

    /** Collapses runs of whitespace but keeps paragraph breaks, which carry real structure. */
    private function collapseBlocks(string $text): string
    {
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/[ \t\x{00A0}]+/u', ' ', $text) ?? $text;
        $text = preg_replace('/(\s*\n\s*){2,}/u', "\n\n", $text) ?? $text;
        $lines = array_map('trim', explode("\n", $text));
        $lines = array_values(array_filter($lines, fn ($l) => $l !== ''));
        return trim(implode("\n", $lines));
    }

    // ------------------------------------------------------------------ link ranking

    /**
     * Picks which of the page's own links are worth spending a fetch on. Links whose visible text
     * or slug echoes the retreat name score highest — that is what isolates one retreat on a site
     * that lists many — followed by the usual content-bearing sections.
     *
     * @param array<int, array{url:string, text:string}> $links
     * @return string[]
     */
    private function rankLinks(array $links, string $baseUrl, string $retreatName): array
    {
        $host = parse_url($baseUrl, PHP_URL_HOST);
        $nameTokens = $this->tokens($retreatName);

        $scored = [];
        foreach ($links as $link) {
            $url = $this->stripFragment($link['url']);
            if ($url === '' || $url === $this->stripFragment($baseUrl)) {
                continue;
            }
            if (parse_url($url, PHP_URL_HOST) !== $host) {
                continue; // same site only
            }
            if (preg_match('~\.(pdf|jpe?g|png|gif|zip|docx?|xlsx?|mp4|webp)(\?|$)~i', $url)) {
                continue;
            }
            if (isset($scored[$url])) {
                continue;
            }

            $haystack = Str::lower($url . ' ' . $link['text']);
            $score = 0;

            foreach ($nameTokens as $token) {
                if (Str::contains($haystack, $token)) {
                    $score += 5;
                }
            }
            foreach (self::USEFUL_SLUGS as $slug) {
                if (Str::contains($haystack, $slug)) {
                    $score += 2;
                    break;
                }
            }
            // Prefer shallower URLs when scores tie; deep archive pages are rarely the retreat page.
            $score -= substr_count(trim((string) parse_url($url, PHP_URL_PATH), '/'), '/');

            if ($score > 0) {
                $scored[$url] = $score;
            }
        }

        arsort($scored);
        return array_slice(array_keys($scored), 0, self::MAX_PAGES * 2);
    }

    private function stripFragment(string $url): string
    {
        return preg_replace('/#.*$/', '', $url) ?? $url;
    }

    /** @return string[] */
    private function tokens(string $text): array
    {
        $stop = ['retreat', 'retreats', 'day', 'days', 'night', 'nights', 'the', 'and', 'for', 'with', 'in', 'at', 'of', 'a'];
        $words = preg_split('/[^a-z0-9]+/i', Str::lower($text)) ?: [];
        return array_values(array_filter($words, fn ($w) => Str::length($w) > 2 && !in_array($w, $stop, true)));
    }
}
