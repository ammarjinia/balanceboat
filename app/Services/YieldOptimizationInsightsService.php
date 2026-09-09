<?php

namespace App\Services;

use App\Bookings;
use App\Experiences;
use App\ExperienceView;
use App\Inquiry;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Powers the "AI Yield Optimization Insights" panel on
 * resources/views/center_panel/experiences.blade.php.
 *
 * It gathers this center's real numbers (per-program views / inquiries / bookings, draft vs live
 * counts, discount configuration, visitor geography) and asks an LLM to turn them into three short
 * revenue-optimization insight cards written in the same confident data-analyst voice as the
 * placeholder copy the panel used to ship with ("Demand Matrix Detection", "Psychological Pricing
 * Hook", ...) — but grounded only in the figures we hand it, never invented.
 *
 * Same Gemini setup as RetreatContentStructuringService (config('services.gemini.*')). The result
 * is cached per center, keyed by a fingerprint of the underlying counts, so the panel doesn't make
 * an API call on every page load but still refreshes as soon as views/inquiries/bookings move.
 * If the key is missing or the call fails, a deterministic rule-based set of insights is returned
 * instead so the panel is never empty.
 */
class YieldOptimizationInsightsService
{
    private const TONES = ['purple', 'emerald', 'amber', 'rose'];

    public function __construct(
        private ?string $apiKey = null,
        private ?string $model = null,
    ) {
        $this->apiKey = $this->apiKey ?: config('services.gemini.key');
        $this->model = $this->model ?: config('services.gemini.model', 'gemini-2.0-flash');
    }

    /**
     * @return array<int, array{tag: string, tone: string, text: string}>
     */
    public function forCenter($centerId): array
    {
        $metrics = $this->collectMetrics($centerId);

        $fingerprint = md5(json_encode([
            $metrics['totals'],
            $metrics['programs'],
        ]));

        return Cache::remember(
            "center_yield_insights_{$centerId}_{$fingerprint}",
            now()->addHours(6),
            function () use ($metrics) {
                try {
                    $insights = $this->callGemini($metrics);
                    if (!empty($insights)) {
                        return $insights;
                    }
                } catch (\Throwable $e) {
                    Log::warning('Yield optimization insights: AI generation failed, using rule-based fallback.', [
                        'error' => $e->getMessage(),
                    ]);
                }

                return $this->ruleBasedInsights($metrics);
            }
        );
    }

    // ---------------------------------------------------------------------

    /**
     * @return array{
     *   totals: array<string, int>,
     *   programs: array<int, array<string, mixed>>,
     *   top_countries: array<string, int>
     * }
     */
    private function collectMetrics($centerId): array
    {
        $programs = Experiences::where('center_id', $centerId)
            ->get(['id', 'name', 'is_draft', 'duration', 'eirly_bird_discount', 'offer_discount']);
        $ids = $programs->pluck('id')->all();

        $countBy = function (string $model) use ($ids) {
            if (empty($ids)) {
                return collect();
            }
            return $model::whereIn('experience_id', $ids)
                ->select('experience_id', DB::raw('count(*) as cnt'))
                ->groupBy('experience_id')
                ->pluck('cnt', 'experience_id');
        };

        $viewsBy     = $countBy(ExperienceView::class);
        $bookingsBy  = $countBy(Bookings::class);
        $inquiriesBy = $countBy(Inquiry::class);

        $rows = $programs->map(function ($p) use ($viewsBy, $bookingsBy, $inquiriesBy) {
            return [
                'name'         => (string) $p->name,
                'status'       => $p->is_draft ? 'draft' : 'live',
                'duration_days' => $p->duration ? (int) $p->duration : null,
                'views'        => (int) ($viewsBy[$p->id] ?? 0),
                'inquiries'    => (int) ($inquiriesBy[$p->id] ?? 0),
                'bookings'     => (int) ($bookingsBy[$p->id] ?? 0),
                'has_discount' => !empty($p->eirly_bird_discount) || !empty($p->offer_discount),
            ];
        })
        ->sortByDesc(fn ($r) => $r['views'] + $r['bookings'] * 20 + $r['inquiries'] * 5)
        ->take(12)
        ->values()
        ->all();

        $topCountries = empty($ids)
            ? []
            : ExperienceView::whereIn('experience_id', $ids)
                ->whereNotNull('country_name')
                ->select('country_name', DB::raw('count(*) as cnt'))
                ->groupBy('country_name')
                ->orderByDesc('cnt')
                ->limit(5)
                ->pluck('cnt', 'country_name')
                ->all();

        return [
            'totals' => [
                'programs'        => $programs->count(),
                'live'            => $programs->where('is_draft', 0)->count(),
                'draft'           => $programs->where('is_draft', 1)->count(),
                'live_no_discount' => $programs->filter(fn ($p) => !$p->is_draft && empty($p->eirly_bird_discount) && empty($p->offer_discount))->count(),
                'views'           => (int) $viewsBy->sum(),
                'inquiries'       => (int) $inquiriesBy->sum(),
                'bookings'        => (int) $bookingsBy->sum(),
            ],
            'programs'      => $rows,
            'top_countries' => $topCountries,
        ];
    }

    private function callGemini(array $metrics): array
    {
        if (empty($this->apiKey)) {
            throw new RuntimeException('GEMINI_API_KEY is not configured.');
        }

        $system = <<<SYS
You are a revenue / yield optimization analyst for a yoga and wellness retreat booking marketplace.
You will be given the real performance numbers for one center's retreat programs. Produce exactly
three short "yield optimization insight" cards for that center's dashboard.

Voice: confident, specific, data-analyst — the same register as these examples (structure only, do
not reuse their wording or numbers):
  - tag "Demand Matrix Detection": "7-day detox structures are drawing 42% more search interest from
    Central Europe. Dynamic localization recommended."
  - tag "Psychological Pricing Hook": "Activating an early-bird 10% markdown captures long-tail
    reservations and lifts booking velocity."
  - tag "Conversion Uplift Signal": "Adding a 21-day silent variant expands your addressable market
    by an estimated 3x."

Hard rules, no exceptions:
1. Use ONLY the numbers and program names in the provided data. Never invent figures, percentages,
   regions, dates, or program names. If you state a number it must be present in or directly
   computable from the data (a ratio of two given counts is fine; a made-up "42%" is not).
2. Each insight must be actionable: name the lever the center can pull (pricing, availability,
   photos/copy, a new duration variant, publishing a draft, localization for a real top country).
3. If the center has almost no data (few programs, no views/inquiries/bookings), the insights
   should be about getting the fundamentals in place (publish drafts, add durations, share links),
   still phrased in the analyst voice, still with no invented metrics.
4. "text" is one or two sentences, under ~240 characters, wrapped in double quotes.
5. "tag" is a 2-4 word analyst-style label (Title Case), e.g. "Demand Concentration Risk".
6. "tone" is one of: purple (neutral/observation), emerald (positive/opportunity),
   amber (caution/attention), rose (risk). Pick what fits the message.
7. Respond with ONLY a single JSON object, no prose, exactly this shape:
{"insights":[{"tag":"...","tone":"...","text":"\"...\""}, {...}, {...}]}
SYS;

        $user = "Center performance data (JSON):\n"
            . json_encode($metrics, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";

        $response = Http::timeout(60)
            ->withHeaders(['x-goog-api-key' => $this->apiKey])
            ->post($url, [
                'system_instruction' => ['parts' => [['text' => $system]]],
                'contents' => [
                    ['role' => 'user', 'parts' => [['text' => $user]]],
                ],
                'generationConfig' => [
                    'temperature' => 0.6,
                    'responseMimeType' => 'application/json',
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Gemini request failed: ' . $response->status() . ' ' . $response->body());
        }

        $content = $response->json('candidates.0.content.parts.0.text');
        $decoded = json_decode((string) $content, true);
        if (!is_array($decoded) || empty($decoded['insights']) || !is_array($decoded['insights'])) {
            throw new RuntimeException('Gemini returned a response without a usable "insights" array.');
        }

        return $this->sanitize($decoded['insights']);
    }

    /**
     * @param array<int, mixed> $raw
     * @return array<int, array{tag: string, tone: string, text: string}>
     */
    private function sanitize(array $raw): array
    {
        $clean = [];
        foreach ($raw as $item) {
            if (!is_array($item)) {
                continue;
            }
            $tag = trim(strip_tags((string) ($item['tag'] ?? '')));
            $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) ($item['text'] ?? ''))));
            $tone = strtolower(trim((string) ($item['tone'] ?? 'purple')));

            if ($tag === '' || $text === '') {
                continue;
            }
            if (!in_array($tone, self::TONES, true)) {
                $tone = 'purple';
            }
            // Keep the quoted styling of the original panel copy, without doubling quotes.
            $text = trim($text, '"');
            $text = '"' . $text . '"';

            $clean[] = ['tag' => $this->limitWords($tag, 4), 'tone' => $tone, 'text' => $text];
            if (count($clean) === 3) {
                break;
            }
        }
        return $clean;
    }

    /**
     * Trims an AI tag to at most $max words so an over-long label can't break the card layout.
     */
    private function limitWords(string $value, int $max): string
    {
        $words = preg_split('/\s+/', trim($value)) ?: [];
        return implode(' ', array_slice($words, 0, $max));
    }

    /**
     * Deterministic fallback used when the AI key is absent or the call fails, so the panel always
     * has grounded content. Same numbers the AI would have seen, expressed as fixed sentences.
     *
     * @return array<int, array{tag: string, tone: string, text: string}>
     */
    private function ruleBasedInsights(array $metrics): array
    {
        $t = $metrics['totals'];
        $programs = collect($metrics['programs']);
        $insights = [];

        $topViewed = $programs->sortByDesc('views')->first();
        if ($topViewed && $topViewed['views'] > 0) {
            $insights[] = [
                'tag'  => 'Demand Concentration',
                'tone' => 'purple',
                'text' => '"' . $topViewed['name'] . '" is your strongest demand driver with ' . number_format($topViewed['views'])
                    . ' tracked visitor' . ($topViewed['views'] == 1 ? '' : 's') . '. Keep its dates and pricing current to convert that traffic."',
            ];
        } else {
            $insights[] = [
                'tag'  => 'Demand Signal',
                'tone' => 'purple',
                'text' => '"No visitor activity is being tracked yet. Publish your programs and share their links to start building the demand history these insights read from."',
            ];
        }

        $leaky = $programs->first(fn ($p) => $p['views'] >= 5 && $p['bookings'] === 0);
        if ($leaky) {
            $insights[] = [
                'tag'  => 'Conversion Uplift Signal',
                'tone' => 'amber',
                'text' => '"' . $leaky['name'] . '" has ' . number_format($leaky['views']) . ' visitors but no bookings. Refresh its photos, confirm upcoming dates, and test an intro price to unlock that pipeline."',
            ];
        } else {
            $best = $programs->sortByDesc('bookings')->first();
            if ($best && $best['bookings'] > 0) {
                $insights[] = [
                    'tag'  => 'Conversion Uplift Signal',
                    'tone' => 'emerald',
                    'text' => '"' . $best['name'] . '" is converting best with ' . $best['bookings'] . ' booking' . ($best['bookings'] == 1 ? '' : 's')
                        . '. Reuse its structure and pricing as the template for new programs."',
                ];
            }
        }

        if ($t['live'] > 0 && $t['live_no_discount'] > 0) {
            $insights[] = [
                'tag'  => 'Psychological Pricing Hook',
                'tone' => 'purple',
                'text' => '"' . $t['live_no_discount'] . ' of your ' . $t['live'] . ' live program' . ($t['live'] == 1 ? '' : 's')
                    . ' run with no early-bird or seasonal discount. A small advance-booking incentive typically pulls reservations forward."',
            ];
        } elseif ($t['draft'] > 0) {
            $insights[] = [
                'tag'  => 'Portfolio Coverage',
                'tone' => 'amber',
                'text' => '"You have ' . $t['draft'] . ' program' . ($t['draft'] == 1 ? '' : 's')
                    . ' still in draft. Publishing widens the range of dates and durations travellers can find you for."',
            ];
        } elseif ($t['programs'] > 0) {
            $insights[] = [
                'tag'  => 'Portfolio Coverage',
                'tone' => 'emerald',
                'text' => '"All ' . $t['programs'] . ' program' . ($t['programs'] == 1 ? '' : 's') . ' are published and live in distribution."',
            ];
        }

        if (empty($insights)) {
            $insights[] = [
                'tag'  => 'Getting Started',
                'tone' => 'purple',
                'text' => '"Create your first retreat program to unlock demand, pricing, and conversion insights here."',
            ];
        }

        return array_slice($insights, 0, 3);
    }
}
