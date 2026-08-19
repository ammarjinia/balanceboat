<?php

namespace App\Services;

use App\Amenities;
use App\Centers;
use App\Experiences;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Takes whatever raw content a center has on file for a retreat (long rambling paragraphs, a
 * one-line note, a comma-dumped list, half-finished HTML from a WYSIWYG editor — the "Breakfast of
 * breakfast" style garbling included) and asks an LLM to re-express it as clean content in the
 * exact shape resources/views/experience_detail.blade.php expects for that field: HTML paragraphs
 * for prose fields, plain newline-separated lines for the two fields the template strips tags and
 * regex-splits on ($experience_highlights, $experience_summary), <ul><li> HTML for the two fields
 * the template renders as a bulleted list (what_is_included / what_is_not_included), and free-text
 * amenity mentions matched against real Amenities rows rather than invented IDs.
 *
 * Hard rule enforced in the prompt: reorganize and clean up what's there, never invent facts (no
 * new prices, dates, counts, or claims that aren't already in the source). A field with no usable
 * source content comes back empty rather than filled with generic marketing filler — same
 * "no fabricated data" rule already followed by partials.experience-food's comments and the
 * rd-* sections built earlier in experience_detail.blade.php.
 *
 * Usage is always two-step: preview() returns a diff (never touches the database), apply() writes
 * only the fields the caller explicitly accepts. See:
 *   - app/Console/Commands/StructureRetreatContent.php (Artisan command, single or --all)
 *   - app/Http/Controllers/Center/CenterContentAiController.php (center-panel "Structure with AI" button)
 */
class RetreatContentStructuringService
{
    /**
     * type:
     *   html_paragraphs — clean HTML (<p>, occasional <strong> sub-heading), rendered via {!! !!}
     *   plain_lines     — plain text, one point per line, NO markup and NO leading bullet/dash
     *                      (the template strip_tags()s and preg_split()s on newlines, then
     *                      re-renders each line as its own <li> with a CSS bullet)
     *   html_list       — <ul><li>...</li></ul>, rendered via {!! !!}
     */
    private const EXPERIENCE_FIELDS = [
        'experience_overview'  => ['label' => 'Overview', 'type' => 'html_paragraphs', 'length' => 'long prose, 2-4 short paragraphs'],
        'experience_highlights'=> ['label' => 'Highlights', 'type' => 'plain_lines', 'length' => 'short list, 4-8 punchy lines, a few words to one short sentence each'],
        'experience_summary'   => ['label' => 'At a Glance', 'type' => 'plain_lines', 'length' => 'short list, 3-6 very short lines'],
        'what_is_included'     => ['label' => "What's Included", 'type' => 'html_list', 'length' => 'short list, one line per item'],
        'what_is_not_included' => ['label' => "What's Not Included", 'type' => 'html_list', 'length' => 'short list, one line per item'],
        'food_overview'        => ['label' => 'Food & Dining', 'type' => 'html_paragraphs', 'length' => 'medium prose, 1-2 short paragraphs'],
        'experience_details'   => ['label' => 'More Details', 'type' => 'html_paragraphs', 'length' => 'long prose, can keep sub-headings as <p><strong>...</strong></p>'],
        'schedule'              => ['label' => 'Daily Schedule (fallback text)', 'type' => 'html_paragraphs', 'length' => 'medium, ideally one <p> per time block, e.g. "<p><strong>08:00</strong> — Morning practice</p>"'],
    ];

    private const CENTER_FIELDS = [
        'about_center'                    => ['label' => 'About the Center', 'type' => 'html_paragraphs', 'length' => 'long prose, 2-4 short paragraphs'],
        'our_mission'                      => ['label' => 'Our Mission', 'type' => 'html_paragraphs', 'length' => 'medium prose, 1 short paragraph'],
        'our_philosophy'                   => ['label' => 'Our Philosophy', 'type' => 'html_paragraphs', 'length' => 'medium prose, 1 short paragraph'],
        'what_sets_us_apart'               => ['label' => 'What Sets Us Apart', 'type' => 'html_paragraphs', 'length' => 'medium prose, 1 short paragraph'],
        'center_highlights'                => ['label' => 'Center Highlights', 'type' => 'html_list', 'length' => 'short list, one line per item'],
        'how_to_get_there'                 => ['label' => 'How to Reach', 'type' => 'html_paragraphs', 'length' => 'medium, keep any By Plane / By Train / By Road structure as <p><strong>By Plane</strong></p> followed by a <p> of directions'],
        'things_to_do_around_the_center'   => ['label' => 'Things to Do Nearby', 'type' => 'html_paragraphs', 'length' => 'short-medium prose or a short list of activities'],
    ];

    public function __construct(
        private ?string $apiKey = null,
        private ?string $model = null,
    ) {
        $this->apiKey = $this->apiKey ?: config('services.openai.key');
        $this->model = $this->model ?: config('services.openai.model', 'gpt-4o-mini');
    }

    /**
     * Builds the before/after diff for one experience (and its center) without writing anything.
     *
     * @return array{
     *   experience: array<string, array{label:string, type:string, before:?string, after:?string}>,
     *   center: array<string, array{label:string, type:string, before:?string, after:?string}>,
     *   amenities: array{before: string[], suggested: string[], unmatched: string[]},
     * }
     */
    public function preview(Experiences $experience): array
    {
        $center = Centers::find($experience->center_id);

        $experienceRaw = $this->collectRaw($experience, self::EXPERIENCE_FIELDS);
        $centerRaw = $center ? $this->collectRaw($center, self::CENTER_FIELDS) : [];

        // Skip the round trip entirely for a record with nothing worth structuring.
        $hasContent = collect($experienceRaw)->merge($centerRaw)->contains(fn ($v) => trim((string) $v) !== '');
        if (!$hasContent) {
            return [
                'experience' => $this->emptyDiff(self::EXPERIENCE_FIELDS, $experienceRaw),
                'center' => $center ? $this->emptyDiff(self::CENTER_FIELDS, $centerRaw) : [],
                'amenities' => ['before' => $this->currentAmenityNames($center), 'suggested' => [], 'unmatched' => []],
            ];
        }

        $structured = $this->callOpenAi($experienceRaw, $centerRaw);

        $experienceDiff = [];
        foreach (self::EXPERIENCE_FIELDS as $key => $meta) {
            $experienceDiff[$key] = [
                'label' => $meta['label'],
                'type' => $meta['type'],
                'before' => $experienceRaw[$key] ?? null,
                'after' => $structured['experience'][$key] ?? null,
            ];
        }

        $centerDiff = [];
        if ($center) {
            foreach (self::CENTER_FIELDS as $key => $meta) {
                $centerDiff[$key] = [
                    'label' => $meta['label'],
                    'type' => $meta['type'],
                    'before' => $centerRaw[$key] ?? null,
                    'after' => $structured['center'][$key] ?? null,
                ];
            }
        }

        $suggestedNames = $structured['amenities'] ?? [];
        [$matched, $unmatched] = $this->matchAmenityNames($suggestedNames);

        return [
            'experience' => $experienceDiff,
            'center' => $centerDiff,
            'amenities' => [
                'before' => $this->currentAmenityNames($center),
                'suggested' => $matched, // ['id' => int, 'name' => string][]
                'unmatched' => $unmatched, // names the AI mentioned that don't match any real Amenities row
            ],
        ];
    }

    /**
     * Writes only the fields present in $accepted. Shape mirrors preview()'s output but every
     * value is just the final string to save (no before/after) — e.g.
     *   ['experience' => ['experience_overview' => '<p>...</p>'], 'center' => [...], 'amenity_ids' => [1,4,9]]
     * Nothing here calls the AI — this is a plain, reviewable database write.
     */
    public function apply(Experiences $experience, array $accepted): void
    {
        foreach ($accepted['experience'] ?? [] as $field => $value) {
            if (!array_key_exists($field, self::EXPERIENCE_FIELDS)) {
                continue; // never let an unexpected key touch an unrelated column
            }
            $experience->{$field} = $value;
        }
        $experience->save();

        if (!empty($accepted['center'])) {
            $center = Centers::find($experience->center_id);
            if ($center) {
                foreach ($accepted['center'] as $field => $value) {
                    if (!array_key_exists($field, self::CENTER_FIELDS)) {
                        continue;
                    }
                    $center->{$field} = $value;
                }
                if (!empty($accepted['amenity_ids'])) {
                    $existing = $this->currentAmenityIds($center);
                    $merged = array_values(array_unique(array_merge($existing, array_map('intval', $accepted['amenity_ids']))));
                    $center->amenities = implode('||', $merged);
                }
                $center->save();
            }
        }
    }

    // ---------------------------------------------------------------------

    private function collectRaw($model, array $fieldMap): array
    {
        $raw = [];
        foreach (array_keys($fieldMap) as $field) {
            $value = $model->{$field} ?? null;
            // Feed the model clean plain text, not whatever half-formed HTML/entities are already
            // stored — this is exactly what turns "Breakfast of breakfast" (a stray WYSIWYG mangling)
            // into readable input instead of compounding it.
            $raw[$field] = trim(html_entity_decode(strip_tags((string) $value)));
        }
        return $raw;
    }

    private function emptyDiff(array $fieldMap, array $raw): array
    {
        $diff = [];
        foreach ($fieldMap as $key => $meta) {
            $diff[$key] = ['label' => $meta['label'], 'type' => $meta['type'], 'before' => $raw[$key] ?? null, 'after' => null];
        }
        return $diff;
    }

    private function currentAmenityNames(?Centers $center): array
    {
        if (!$center || empty($center->amenities)) {
            return [];
        }
        return Amenities::whereIn('id', $this->currentAmenityIds($center))->orderBy('name')->pluck('name')->all();
    }

    private function currentAmenityIds(?Centers $center): array
    {
        if (!$center || empty($center->amenities)) {
            return [];
        }
        return array_values(array_filter(array_map('intval', explode('||', $center->amenities))));
    }

    /**
     * Matches AI-suggested amenity names against real Amenities rows (case-insensitive, substring
     * both ways) instead of ever letting the model invent an amenity_id. Names with no reasonable
     * match are surfaced separately so the reviewer can see what got dropped and why, rather than
     * having them silently disappear.
     */
    private function matchAmenityNames(array $suggestedNames): array
    {
        if (empty($suggestedNames)) {
            return [[], []];
        }
        $all = Amenities::select('id', 'name')->get();
        $matched = [];
        $unmatched = [];
        foreach ($suggestedNames as $name) {
            $name = trim((string) $name);
            if ($name === '') {
                continue;
            }
            $needle = Str::lower($name);
            $hit = $all->first(function ($a) use ($needle) {
                $hay = Str::lower($a->name);
                return $hay === $needle || Str::contains($hay, $needle) || Str::contains($needle, $hay);
            });
            if ($hit) {
                if (!collect($matched)->contains('id', $hit->id)) {
                    $matched[] = ['id' => $hit->id, 'name' => $hit->name];
                }
            } else {
                $unmatched[] = $name;
            }
        }
        return [$matched, $unmatched];
    }

    private function callOpenAi(array $experienceRaw, array $centerRaw): array
    {
        if (empty($this->apiKey)) {
            throw new RuntimeException('OPENAI_API_KEY is not configured — set it in .env to use AI content structuring.');
        }

        $schemaDescription = $this->describeSchema();

        $system = <<<SYS
You are a content editor for a yoga/wellness retreat booking website. You will be given raw,
often messy source text pulled from a center's existing records (rambling notes, repeated
fragments, comma-dumped lists, or nothing at all for some fields).

Rules, no exceptions:
1. Reorganize, clean, and re-express only what is already present in the source text for that
   field. Never invent facts: no new prices, dates, counts, amenities, or claims not implied by
   the given source.
2. If a field's source text is empty or has no real content, return an empty string "" for it.
   Do not fill it with generic marketing filler.
3. Fix garbled/repeated fragments (e.g. source "Breakfast of breakfast" should become just
   "Breakfast" if that's clearly the intent) rather than repeating the garbling.
4. Match each field's requested output type exactly:
   - html_paragraphs: return valid HTML using only <p> and <strong> tags.
   - plain_lines: return PLAIN TEXT ONLY, one point per line separated by \\n, with NO bullet
     characters, dashes, numbers, or HTML — the website adds its own bullet styling.
   - html_list: return HTML as <ul><li>...</li></ul>, one <li> per item, no nested tags beyond
     <strong> if truly needed.
5. Also extract a short list of amenity names implied anywhere in the given text (e.g. "pool",
   "free wifi", "on-site spa") into the "amenities" array — only ones actually mentioned or
   clearly implied, not a generic retreat-center checklist.
6. Respond with ONLY a single JSON object, no prose, matching exactly this shape:
{"experience": {"<field>": "<value>", ...}, "center": {"<field>": "<value>", ...}, "amenities": ["<name>", ...]}
SYS;

        $user = "Field schema (key => type, target length):\n{$schemaDescription}\n\n"
            . "Experience source content (JSON):\n" . json_encode($experienceRaw, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n"
            . "Center source content (JSON):\n" . json_encode($centerRaw, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $response = Http::withToken($this->apiKey)
            ->timeout(90)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'temperature' => 0.3,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $user],
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('OpenAI request failed: ' . $response->status() . ' ' . $response->body());
        }

        $content = $response->json('choices.0.message.content');
        $decoded = json_decode((string) $content, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('OpenAI returned a response that was not valid JSON.');
        }

        return [
            'experience' => $this->sanitizeFields($decoded['experience'] ?? [], self::EXPERIENCE_FIELDS),
            'center' => $this->sanitizeFields($decoded['center'] ?? [], self::CENTER_FIELDS),
            'amenities' => array_values(array_filter((array) ($decoded['amenities'] ?? []))),
        ];
    }

    /**
     * Drops any key the model returned that isn't a real field for this record, and blanks
     * anything that came back as literally empty/whitespace so an empty diff row reads as "no
     * change suggested" rather than a suggestion to overwrite with blank content.
     */
    private function sanitizeFields(array $values, array $fieldMap): array
    {
        $clean = [];
        foreach (array_keys($fieldMap) as $key) {
            $v = $values[$key] ?? null;
            if (is_string($v) && trim(strip_tags($v)) !== '') {
                $clean[$key] = trim($v);
            }
        }
        return $clean;
    }

    private function describeSchema(): string
    {
        $lines = [];
        foreach (self::EXPERIENCE_FIELDS as $key => $meta) {
            $lines[] = "experience.{$key} — {$meta['type']} — {$meta['length']}";
        }
        foreach (self::CENTER_FIELDS as $key => $meta) {
            $lines[] = "center.{$key} — {$meta['type']} — {$meta['length']}";
        }
        return implode("\n", $lines);
    }
}
