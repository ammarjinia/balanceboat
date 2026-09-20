<?php

namespace App\Services;

use App\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Turns the plain text WebPageExtractor pulled off a center's own website into a field map the
 * create-retreat wizard can be filled from, in one Gemini call.
 *
 * Sibling of RetreatContentStructuringService, and deliberately shaped like it — same per-field
 * output types, same sanitize-on-the-way-back discipline, same hard rule that the model
 * reorganizes what it was given and never invents. The differences are what make this a separate
 * class rather than a flag on that one:
 *
 *  - Source is an external website, not the center's existing database rows.
 *  - It fills the whole wizard, including the choice fields (categories, destination, skill level,
 *    languages), not just the prose fields. Those are passed to the model as closed lists of real
 *    Category rows, so a hallucinated taxonomy is impossible by construction rather than by
 *    instruction — the same guarantee matchAmenityNames() gives amenities in the sibling service.
 *  - Nothing here writes to the database. The result is handed to the browser, which fills the
 *    form; the center then presses Create and the existing store path validates it as normal.
 *
 * Money and dates are never filled. A wrong paragraph on a listing is a content problem; a wrong
 * price on a booking page is a commercial one, so prices, deposits and cancellation terms are
 * absent from the schema entirely and the prompt forbids inferring them.
 */
class RetreatImportService
{
    public const SKILL_LEVELS = ['All Levels', 'Beginner', 'Intermediate', 'Advanced'];
    public const LANGUAGES = ['English', 'Hindi', 'German', 'Spanish', 'French', 'Italian', 'Russian', 'Mandarin'];

    /**
     * Keyed by the form control name on experience_form.blade.php, so the browser can fill by
     * [name="..."] without a translation table.
     *
     * type:
     *   html_paragraphs — <p>/<strong> only, goes into a TinyMCE field
     *   html_list       — <ul><li>…</li></ul>
     *   plain_lines     — plain text, one point per line, no bullets or markup
     *   schedule_lines  — plain text, one "HH:MM  Activity" per line (scheduleBuilder parses this)
     *   text_line       — one short plain line
     *   csv_line        — short comma-separated keywords
     *   seo_title / seo_description — one plain line, length-capped
     *   integer, url, gps, integer_list, enum, enum_list — as named
     *
     * verify: always flag this field for the center to check, however confident the model sounds.
     *         Reserved for things that are cheap to get wrong and expensive to publish wrong.
     * important: worth telling the center about when it comes back empty. Most empty fields are
     *         not worth a warning — the wizard already shows them empty with a placeholder, and
     *         an amber note on all twenty would bury the two that matter.
     * step:   which wizard step the field lives on, so the UI can point at it.
     */
    private const FIELDS = [
        'experience_summary'   => ['label' => 'Short Summary', 'type' => 'plain_lines', 'step' => 1, 'important' => true, 'length' => '3-6 very short lines, one fact each'],
        'experience_overview'  => ['label' => 'Full Description', 'type' => 'html_paragraphs', 'step' => 1, 'important' => true, 'length' => '2-4 short paragraphs'],
        'batch_size'           => ['label' => 'Max Guest Capacity', 'type' => 'integer', 'step' => 1, 'verify' => true, 'length' => 'a whole number of guests, only if the source states a group size'],
        'language_spoken'      => ['label' => 'Languages Spoken', 'type' => 'enum_list', 'step' => 1, 'options' => self::LANGUAGES, 'length' => 'only languages the source actually mentions'],
        'skill_level'          => ['label' => 'Skill Level', 'type' => 'enum', 'step' => 1, 'options' => self::SKILL_LEVELS, 'length' => 'pick one; use "All Levels" only if the source says all levels are welcome'],
        'atmosphere'           => ['label' => 'Atmosphere / Setting', 'type' => 'csv_line', 'step' => 1, 'length' => '2-5 setting keywords, e.g. Beachfront, Jungle, Mountain'],
        'gps'                  => ['label' => 'GPS Coordinates', 'type' => 'gps', 'step' => 1, 'verify' => true, 'length' => 'only if exact coordinates appear in the source; otherwise empty'],
        'tags'                 => ['label' => 'Tags', 'type' => 'csv_line', 'step' => 1, 'length' => '5-10 comma-separated search keywords'],

        'category_names'       => ['label' => 'Retreat Types', 'type' => 'enum_list', 'step' => 2, 'important' => true, 'length' => 'every type that genuinely applies, chosen ONLY from the allowed list'],
        'destination_names'    => ['label' => 'Destination', 'type' => 'enum_list', 'step' => 2, 'important' => true, 'length' => 'usually one, chosen ONLY from the allowed list'],

        'durations'            => ['label' => 'Duration Packages', 'type' => 'integer_list', 'step' => 3, 'verify' => true, 'important' => true, 'length' => 'nights per package, e.g. [7, 14], only where the source states a length'],
        'food'                 => ['label' => 'Food Type', 'type' => 'csv_line', 'step' => 3, 'length' => 'e.g. Vegetarian, Vegan, Sattvic, Organic'],
        'food_overview'        => ['label' => 'Food & Dining', 'type' => 'html_paragraphs', 'step' => 3, 'length' => '1-2 short paragraphs'],
        'area'                 => ['label' => 'Area / Location', 'type' => 'text_line', 'step' => 3, 'important' => true, 'length' => 'one line, e.g. "Near Kovalam Beach, Thiruvananthapuram, Kerala"'],
        'meta_title'           => ['label' => 'Meta Title', 'type' => 'seo_title', 'step' => 3, 'length' => 'under 60 characters, leads with retreat type + destination'],
        'meta_description'     => ['label' => 'Meta Description', 'type' => 'seo_description', 'step' => 3, 'length' => 'under 155 characters, names the main benefit and the location'],

        'experience_schedule'  => ['label' => 'Daily Schedule', 'type' => 'schedule_lines', 'step' => 4, 'verify' => true, 'length' => 'one "HH:MM  Activity" per line in 24-hour time, ordered through the day'],
        'experience_highlights'=> ['label' => 'Highlights', 'type' => 'plain_lines', 'step' => 4, 'important' => true, 'length' => '4-8 punchy lines, a few words each'],
        'experience_details'   => ['label' => 'Full Program Details', 'type' => 'html_paragraphs', 'step' => 4, 'length' => 'long prose; keep sub-headings as <p><strong>…</strong></p>'],
        'what_is_included'     => ['label' => "What's Included", 'type' => 'html_list', 'step' => 4, 'important' => true, 'length' => 'one <li> per item'],
        'what_is_not_included' => ['label' => "What's Not Included", 'type' => 'html_list', 'step' => 4, 'length' => 'one <li> per item'],
        'how_to_get_here'      => ['label' => 'How to Get Here', 'type' => 'html_paragraphs', 'step' => 4, 'length' => 'keep any By Plane / By Train / By Road structure as <p><strong>By Plane</strong></p> then a <p>'],

        'video_url'            => ['label' => 'Video URL', 'type' => 'url', 'step' => 5, 'verify' => true, 'length' => 'a YouTube or Vimeo link if one appears in the source'],
    ];

    public function __construct(
        private ?string $apiKey = null,
        private ?string $model = null,
    ) {
        $this->apiKey = $this->apiKey ?: config('services.gemini.key');
        $this->model = $this->model ?: config('services.gemini.model', 'gemini-2.0-flash');
    }

    /**
     * @param array<int, array{url:string, title:string, text:string}> $pages
     * @return array{
     *   fields: array<string, array{label:string, type:string, step:int, value:mixed, source_url:string, confidence:string, verify:bool}>,
     *   missing: array<int, array{name:string, label:string, step:int}>,
     *   found_retreat: bool,
     *   filled_count: int,
     * }
     */
    public function generate(array $pages, string $retreatName, string $pastedText = ''): array
    {
        $categories = Category::where('type', 0)->where('parent', 0)->orderBy('name')->pluck('name', 'id');
        $destinations = Category::where('type', 1)->where('parent', 0)->orderBy('name')->pluck('name', 'id');

        $raw = $this->callGemini($pages, $retreatName, $pastedText, $categories->values()->all(), $destinations->values()->all());

        $fields = [];
        $missing = [];

        foreach (self::FIELDS as $name => $meta) {
            $entry = $raw['fields'][$name] ?? null;
            $value = $this->sanitize($entry['value'] ?? null, $name, $meta, $categories, $destinations);

            if ($this->isEmptyValue($value)) {
                // Only the fields a listing genuinely needs are reported as missing. Warning about
                // every empty field would hand the center a twenty-item to-do list and bury the
                // two or three that actually matter.
                if (!empty($meta['important'])) {
                    $missing[] = ['name' => $name, 'label' => $meta['label'], 'step' => $meta['step']];
                }
                continue;
            }

            $confidence = Str::lower((string) ($entry['confidence'] ?? 'medium'));
            if (!in_array($confidence, ['high', 'medium', 'low'], true)) {
                $confidence = 'medium';
            }

            $fields[$name] = [
                'label' => $meta['label'],
                'type' => $meta['type'],
                'step' => $meta['step'],
                'value' => $value,
                'source_url' => $this->safeSourceUrl($entry['source_url'] ?? '', $pages),
                'confidence' => $confidence,
                'verify' => (bool) ($meta['verify'] ?? false),
            ];
        }

        return [
            'fields' => $fields,
            'missing' => $missing,
            'found_retreat' => (bool) ($raw['found_retreat'] ?? true),
            'filled_count' => count($fields),
        ];
    }

    /** Maps accepted category/destination names back to the checkbox IDs the form submits. */
    public function categoryIdsFor(array $names): array
    {
        if (empty($names)) {
            return [];
        }
        $rows = Category::whereIn('type', [0, 1])->where('parent', 0)->get(['id', 'name']);
        $ids = [];
        foreach ($names as $name) {
            $hit = $rows->first(fn ($c) => Str::lower($c->name) === Str::lower(trim((string) $name)));
            if ($hit) {
                $ids[] = (int) $hit->id;
            }
        }
        return array_values(array_unique($ids));
    }

    // ------------------------------------------------------------------

    private function callGemini(array $pages, string $retreatName, string $pastedText, array $categories, array $destinations): array
    {
        if (empty($this->apiKey)) {
            throw new RuntimeException('GEMINI_API_KEY is not configured — set it in .env to use AI import.');
        }

        $system = <<<SYS
You are a listings editor for a yoga and wellness retreat booking website. You are given the plain
text of a retreat center's own public web pages, and the name of ONE retreat the center wants to
list. Extract that retreat's details into a strict JSON object.

Rules, no exceptions:

1. NEVER invent facts. Every value must be supported by the supplied text. If the text does not
   support a field, return "" for it (or [] for a list field). An empty field is correct and
   expected; generic marketing filler is a failure.
2. NEVER output prices, deposits, payment terms, cancellation terms, or calendar dates in ANY
   field, even when the source states them. Those are entered separately by the center. If a
   paragraph you are rewriting mentions a price, omit that sentence.
3. Focus on the named retreat. If the site covers several retreats, use the sections about this one
   and ignore the others. Center-wide facts (location, how to reach, food, languages) may come from
   anywhere on the site. If you cannot find this retreat at all, set "found_retreat" to false and
   still fill whatever center-wide fields the text supports.
4. For "category_names" and "destination_names" you MUST choose only from the allowed lists given
   below, copied exactly. Never output a name that is not on the list. Return [] rather than
   approximating.
5. Match each field's output type exactly:
   - html_paragraphs: valid HTML using only <p> and <strong>.
   - html_list: <ul><li>…</li></ul>, one <li> per item.
   - plain_lines: PLAIN TEXT, one point per line separated by \\n, no bullets, dashes, numbers or HTML.
   - schedule_lines: PLAIN TEXT, one line per time block as "HH:MM  Activity" in 24-hour time.
   - text_line / csv_line: one plain line, no markup.
   - seo_title: one plain line, 60 characters or fewer. seo_description: one plain line, 155 or fewer.
   - integer: a bare number. integer_list: an array of bare numbers.
   - enum / enum_list: a value, or array of values, copied exactly from that field's allowed list.
   - url: a single absolute http(s) link.
6. Write clean, scannable, search-friendly copy: short paragraphs, concrete specifics (practices,
   therapies, who it suits, tangible outcomes), no hyperbole such as "life-changing" or "magical".
7. For every field you fill, report where it came from in "source_url" (one of the supplied page
   URLs) and how sure you are in "confidence": "high" if stated plainly in the text, "medium" if
   assembled from several places, "low" if you are reading between the lines.
8. Respond with ONLY this JSON object and nothing else:
{"found_retreat": true, "fields": {"<field>": {"value": <string|array|number>, "source_url": "<url>", "confidence": "high"}}}
SYS;

        $user = "Retreat to list: \"{$retreatName}\"\n\n"
            . "Allowed retreat type names (choose category_names only from these):\n"
            . json_encode(array_values($categories), JSON_UNESCAPED_SLASHES) . "\n\n"
            . "Allowed destination names (choose destination_names only from these):\n"
            . json_encode(array_values($destinations), JSON_UNESCAPED_SLASHES) . "\n\n"
            . "Allowed skill_level values: " . json_encode(self::SKILL_LEVELS) . "\n"
            . "Allowed language_spoken values: " . json_encode(self::LANGUAGES) . "\n\n"
            . "Field schema (field — type — target):\n" . $this->describeSchema() . "\n\n"
            . "SOURCE PAGES:\n" . $this->describePages($pages);

        if (trim($pastedText) !== '') {
            $user .= "\n\nADDITIONAL TEXT PASTED BY THE CENTER (treat as source material of the highest "
                . "reliability, source_url \"pasted\"):\n" . Str::limit(trim($pastedText), 20000, '');
        }

        $response = $this->postWithRetry([
            'system_instruction' => ['parts' => [['text' => $system]]],
            'contents' => [['role' => 'user', 'parts' => [['text' => $user]]]],
            'generationConfig' => [
                'temperature' => 0.2,
                'responseMimeType' => 'application/json',
                // Around twenty fields of prose is a big answer. Left at the default this runs out
                // of room mid-object, and truncated JSON is indistinguishable from a broken model.
                'maxOutputTokens' => 8192,
            ],
        ]);

        $finishReason = $response->json('candidates.0.finishReason');
        $blockReason = $response->json('promptFeedback.blockReason');
        $text = (string) $response->json('candidates.0.content.parts.0.text');

        if ($blockReason) {
            Log::warning('Gemini blocked the prompt', ['reason' => $blockReason]);
            throw new RuntimeException('The AI service declined to process your website content. Please contact support.');
        }

        if ($finishReason === 'MAX_TOKENS') {
            Log::warning('Gemini response truncated', ['chars' => strlen($text)]);
            throw new RuntimeException(
                'There was too much content on your website for one pass. Try the web address of this '
                . 'one retreat rather than your whole site.'
            );
        }

        $decoded = json_decode($text, true);
        if (!is_array($decoded)) {
            Log::warning('Gemini returned unparseable JSON', [
                'finish_reason' => $finishReason,
                'sample' => Str::limit($text, 500),
            ]);
            throw new RuntimeException('The AI service returned an unreadable response. Please try again.');
        }

        return $decoded;
    }

    /**
     * Posts to Gemini, retrying the statuses that mean "busy, ask again" rather than "your request
     * is wrong". A 503 is the model being overloaded and usually clears within seconds, so giving
     * up on the first one throws away a website read that already took twenty seconds — the center
     * then repeats the whole thing by hand.
     *
     * Retries are cheap in wall-clock terms because an overload is rejected almost immediately;
     * it is a slow success, not a slow failure, that costs time. Attempts are capped so this can
     * never approach a shared host's max_execution_time.
     */
    private function postWithRetry(array $payload): \Illuminate\Http\Client\Response
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
        $retryable = [429, 500, 502, 503, 504];
        $maxAttempts = 3;
        $response = null;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $response = Http::timeout(60)
                    ->withHeaders(['x-goog-api-key' => $this->apiKey])
                    ->post($url, $payload);
            } catch (\Throwable $e) {
                Log::warning('Gemini request threw', ['attempt' => $attempt, 'error' => $e->getMessage()]);
                if ($attempt === $maxAttempts) {
                    throw new RuntimeException('We could not reach the AI service. Please try again in a moment.');
                }
                sleep($attempt * 2);
                continue;
            }

            if ($response->successful()) {
                return $response;
            }

            Log::warning('Gemini request failed', [
                'attempt' => $attempt,
                'status' => $response->status(),
                'body' => Str::limit($response->body(), 500),
            ]);

            if (!in_array($response->status(), $retryable, true)) {
                break; // a real rejection (bad key, bad model, quota exhausted) — retrying won't help
            }

            if ($attempt < $maxAttempts) {
                sleep($attempt * 2); // 2s, then 4s
            }
        }

        throw new RuntimeException($this->explainFailure($response));
    }

    private function explainFailure(?\Illuminate\Http\Client\Response $response): string
    {
        $status = $response?->status();

        return match (true) {
            $status === 503 || $status === 429 => 'The AI service is busy right now. Your website was read successfully — '
                . 'please press "Fill my form" again in a minute.',
            $status === 400 => 'The AI service rejected the request. This usually means the API key is wrong or your '
                . 'website content was too large. Please contact support.',
            $status === 403 => 'The AI service refused the request. The API key may be invalid or out of quota. '
                . 'Please contact support.',
            $status === 404 => 'The configured AI model was not found. Please contact support.',
            default => 'The AI service did not respond (' . ($status ?? 'no response') . '). Please try again in a moment.',
        };
    }

    private function describeSchema(): string
    {
        $lines = [];
        foreach (self::FIELDS as $name => $meta) {
            $lines[] = "{$name} — {$meta['type']} — {$meta['length']}";
        }
        return implode("\n", $lines);
    }

    private function describePages(array $pages): string
    {
        $out = [];
        foreach ($pages as $i => $page) {
            $n = $i + 1;
            $out[] = "--- PAGE {$n} · url: {$page['url']} · title: {$page['title']}\n{$page['text']}";
        }
        return implode("\n\n", $out);
    }

    /**
     * Forces the model's output back into the shape the form expects. Anything that cannot be
     * coerced becomes empty, which surfaces as "we couldn't find this" rather than as a broken
     * value quietly written into a field.
     */
    private function sanitize(mixed $value, string $name, array $meta, Collection $categories, Collection $destinations): mixed
    {
        $type = $meta['type'];

        if ($type === 'enum_list' || $type === 'integer_list') {
            if (!is_array($value)) {
                return [];
            }
        } elseif (is_array($value)) {
            $value = implode("\n", array_filter(array_map('strval', $value)));
        }

        switch ($type) {
            case 'integer':
                $int = (int) preg_replace('/\D+/', '', (string) $value);
                return ($int > 0 && $int <= 500) ? $int : '';

            case 'integer_list':
                $ints = array_values(array_unique(array_filter(
                    array_map(fn ($v) => (int) preg_replace('/\D+/', '', (string) $v), $value),
                    fn ($n) => $n >= 1 && $n <= 365
                )));
                sort($ints);
                return $ints;

            case 'enum':
                $options = $meta['options'] ?? [];
                foreach ($options as $option) {
                    if (Str::lower(trim((string) $value)) === Str::lower($option)) {
                        return $option;
                    }
                }
                return '';

            case 'enum_list':
                $options = $meta['options'] ?? null;
                if ($options === null) {
                    $options = $name === 'category_names'
                        ? $categories->values()->all()
                        : $destinations->values()->all();
                }
                $picked = [];
                foreach ($value as $candidate) {
                    foreach ($options as $option) {
                        if (Str::lower(trim((string) $candidate)) === Str::lower($option) && !in_array($option, $picked, true)) {
                            $picked[] = $option;
                        }
                    }
                }
                return $picked;

            case 'url':
                $url = trim((string) $value);
                return preg_match('~^https?://~i', $url) && filter_var($url, FILTER_VALIDATE_URL) ? $url : '';

            case 'gps':
                $gps = trim((string) $value);
                return preg_match('/^-?\d{1,3}(\.\d+)?\s*,\s*-?\d{1,3}(\.\d+)?$/', $gps) ? $gps : '';

            case 'seo_title':
            case 'seo_description':
                $line = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) $value))) ?? '', " \t\n\r\0\x0B\"'");
                $limit = $type === 'seo_title' ? 60 : 155;
                return Str::length($line) > $limit ? rtrim(Str::limit($line, $limit, '')) : $line;

            case 'text_line':
            case 'csv_line':
                return trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) $value))) ?? '');

            case 'plain_lines':
                $lines = array_filter(array_map(
                    fn ($l) => trim(preg_replace('/^[•\-\*\d\.\)\s]+/u', '', strip_tags($l)) ?? ''),
                    explode("\n", (string) $value)
                ));
                return implode("\n", $lines);

            case 'schedule_lines':
                $out = [];
                foreach (explode("\n", strip_tags((string) $value)) as $line) {
                    $line = trim($line);
                    if ($line === '') {
                        continue;
                    }
                    // scheduleBuilder's parser wants "HH:MM  Activity"; anything else it dumps at
                    // 12:00, so normalize here rather than letting the builder guess. An
                    // out-of-range time is dropped rather than clamped — clamping 25:99 to 23:59
                    // would turn obvious nonsense into a plausible-looking row nobody questions.
                    if (preg_match('/^(\d{1,2}):(\d{2})\s*(.*)$/', $line, $m) && trim($m[3]) !== '') {
                        $hour = (int) $m[1];
                        $minute = (int) $m[2];
                        if ($hour <= 23 && $minute <= 59) {
                            $out[] = sprintf('%02d:%02d  %s', $hour, $minute, trim($m[3]));
                        }
                    }
                }
                return implode("\n", $out);

            case 'html_list':
                $html = trim(strip_tags((string) $value, '<ul><ol><li><strong><em>'));
                return Str::contains($html, '<li') ? $html : '';

            case 'html_paragraphs':
            default:
                $html = trim(strip_tags((string) $value, '<p><strong><em><br>'));
                return trim(strip_tags($html)) === '' ? '' : $html;
        }
    }

    private function isEmptyValue(mixed $value): bool
    {
        if (is_array($value)) {
            return empty($value);
        }
        return trim((string) $value) === '';
    }

    /** Only ever echo back a URL we actually fetched, never a string the model made up. */
    private function safeSourceUrl(mixed $candidate, array $pages): string
    {
        $candidate = trim((string) $candidate);
        if ($candidate === 'pasted') {
            return 'pasted';
        }
        foreach ($pages as $page) {
            if ($page['url'] === $candidate) {
                return $candidate;
            }
        }
        return '';
    }
}
