<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Services\RetreatImportService;
use App\Services\WebPageExtractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Backs the "Fill my form" card on the create-retreat wizard (center_panel/partials/ai-import-card).
 *
 * Split into two calls on purpose. Fetching up to six pages and then waiting on Gemini comfortably
 * exceeds a single request's budget on shared hosting, and this app has no queue worker running —
 * the scheduler in routes/console.php is a cron, not a worker, so a queued job could sit for a
 * minute before it even starts while the center watches a progress bar.
 *
 * The extracted page text round-trips through the browser between the two calls rather than being
 * parked server-side, because CACHE_STORE is `array` in this environment and would lose it between
 * requests. That means call two receives text the client could have altered, which is not a new
 * hole: the same endpoint already accepts free-typed text through the "paste instead" box by
 * design, nothing is written to the database here, and the center reviews every field in the
 * wizard before pressing Create.
 *
 * Neither endpoint touches the database. The response fills the form client-side; the existing
 * CenterDashboardController@experienceStore path still does the validating and the saving.
 */
class CenterRetreatImportController extends Controller
{
    /** Bounds on what call two will accept back from the browser, mirroring WebPageExtractor's own caps. */
    private const MAX_PAGES_IN = 6;
    private const MAX_CHARS_PER_PAGE_IN = 16000;
    private const MAX_PASTED_CHARS = 20000;

    public function __construct()
    {
        $this->middleware('center.auth');
    }

    /**
     * Validates, but reports a failure in this feature's own {"error": "..."} shape.
     *
     * Laravel's ValidationException produces {"message": ..., "errors": ...}, which the import
     * card cannot tell apart from a CSRF failure or a 500 — every one of them renders as a bare
     * "something went wrong". Naming the problem here is the difference between a center fixing
     * their input and a center giving up.
     *
     * @return array<string, mixed>|\Illuminate\Http\JsonResponse
     */
    private function validateOrFail(Request $request, array $rules)
    {
        $validator = validator($request->all(), $rules);

        if ($validator->fails()) {
            Log::info('Retreat import validation failed', ['errors' => $validator->errors()->toArray()]);
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        return $validator->validated();
    }

    /**
     * Step one: fetch and reduce the center's website to plain text. Returns the text to the
     * browser, which posts it straight back to generate().
     */
    public function extract(Request $request, WebPageExtractor $extractor)
    {
        $data = $this->validateOrFail($request, [
            'url' => 'required|string|max:2000',
            'retreat_name' => 'required|string|max:255',
        ]);

        if ($data instanceof \Illuminate\Http\JsonResponse) {
            return $data;
        }

        try {
            $result = $extractor->extract($data['url'], $data['retreat_name']);
        } catch (\Throwable $e) {
            Log::info('Retreat import extract failed', ['url' => $data['url'], 'error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json([
            'pages' => $result['pages'],
            'images' => $result['images'],
            'start_url' => $result['start_url'],
            'notes' => $result['notes'],
            'page_count' => count($result['pages']),
        ]);
    }

    /**
     * Step two: hand the extracted text to the model and return a field map keyed by the form
     * control names on experience_form.blade.php.
     */
    public function generate(Request $request, RetreatImportService $service)
    {
        // A page with no readable text is an ordinary outcome, not a client error — a
        // JavaScript-rendered site extracts to nothing. So text is nullable here and blank pages
        // are dropped below, rather than failing validation and surfacing as an opaque 422.
        $data = $this->validateOrFail($request, [
            'retreat_name' => 'required|string|max:255',
            'pages' => 'nullable|array|max:' . self::MAX_PAGES_IN,
            'pages.*.url' => 'required|string|max:2000',
            'pages.*.title' => 'nullable|string|max:500',
            'pages.*.text' => 'nullable|string',
            'pasted_text' => 'nullable|string|max:' . self::MAX_PASTED_CHARS,
        ]);

        if ($data instanceof \Illuminate\Http\JsonResponse) {
            return $data;
        }

        $pages = [];
        foreach ($data['pages'] ?? [] as $page) {
            $text = trim((string) ($page['text'] ?? ''));
            if ($text === '') {
                continue;
            }
            $pages[] = [
                'url' => $page['url'],
                'title' => Str::limit((string) ($page['title'] ?? ''), 500, ''),
                'text' => Str::limit($text, self::MAX_CHARS_PER_PAGE_IN, ''),
            ];
        }

        $pastedText = trim((string) ($data['pasted_text'] ?? ''));

        if (empty($pages) && $pastedText === '') {
            // Distinguish "you gave us nothing" from "we opened your site and it was empty" —
            // the second is the JavaScript-rendered case, and the center needs the paste box.
            $attemptedPages = count($data['pages'] ?? []);
            return response()->json([
                'error' => $attemptedPages > 0
                    ? "We opened your website but couldn't read any text on it. Sites that load their "
                        . "content with JavaScript look empty to us. Please paste your retreat description "
                        . "into the box instead."
                    : 'There was nothing to read. Enter your website address, or paste your retreat description.',
            ], 422);
        }

        try {
            $result = $service->generate($pages, $data['retreat_name'], $pastedText);
        } catch (\Throwable $e) {
            Log::error('Retreat import generate failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 422);
        }

        // Categories and destinations are chosen by name but submitted as checkbox IDs, so resolve
        // them here rather than shipping the whole taxonomy to the browser.
        $categoryIds = $service->categoryIdsFor(array_merge(
            (array) ($result['fields']['category_names']['value'] ?? []),
            (array) ($result['fields']['destination_names']['value'] ?? [])
        ));

        return response()->json([
            'fields' => $result['fields'],
            'missing' => $result['missing'],
            'category_ids' => $categoryIds,
            'found_retreat' => $result['found_retreat'],
            'filled_count' => $result['filled_count'],
        ]);
    }
}
