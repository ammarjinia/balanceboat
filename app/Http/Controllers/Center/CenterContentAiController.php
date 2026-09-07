<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Experiences;
use App\Services\RetreatContentStructuringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Session;

/**
 * Backs the "Structure with AI" button on center_panel/experience_form.blade.php. Two endpoints,
 * matching the preview-then-apply flow in RetreatContentStructuringService: preview() never writes
 * to the database, apply() only writes the specific fields the center user checked and confirmed
 * on the review screen.
 */
class CenterContentAiController extends Controller
{
    public function __construct()
    {
        $this->middleware('center.auth');
    }

    public function preview(Request $request, $experienceId, RetreatContentStructuringService $service)
    {
        $centerId = Session::get('center_id');
        $experience = Experiences::where('id', $experienceId)->where('center_id', $centerId)->first();
        if (!$experience) {
            return response()->json(['error' => 'Retreat not found.'], 404);
        }

        try {
            $diff = $service->preview($experience);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json(['diff' => $diff]);
    }

    public function apply(Request $request, $experienceId, RetreatContentStructuringService $service)
    {
        $centerId = Session::get('center_id');
        $experience = Experiences::where('id', $experienceId)->where('center_id', $centerId)->first();
        if (!$experience) {
            return response()->json(['error' => 'Retreat not found.'], 404);
        }

        // The client only ever sends back fields it displayed on the review screen and the user
        // checked, so this is a controlled write, not an arbitrary mass-assignment from the client.
        // Prefer Laravel's parsed input, but fall back to decoding the raw body ourselves — some
        // setups (proxies, a missing or altered Content-Type header) leave $request->input() empty
        // for a valid JSON POST, which previously looked like a successful save of nothing.
        $payload = $request->all();
        if (empty($payload['experience']) && empty($payload['center'])) {
            $decoded = json_decode((string) $request->getContent(), true);
            if (is_array($decoded)) {
                $payload = $decoded;
            }
        }

        $accepted = [
            'experience' => (array) ($payload['experience'] ?? []),
            'center' => (array) ($payload['center'] ?? []),
            'amenity_ids' => array_map('intval', (array) ($payload['amenity_ids'] ?? [])),
        ];

        Log::info('AI structure apply', [
            'experience_id' => $experienceId,
            'is_json' => $request->isJson(),
            'content_type' => $request->header('Content-Type'),
            'experience_keys' => array_keys($accepted['experience']),
            'center_keys' => array_keys($accepted['center']),
            'raw_len' => strlen((string) $request->getContent()),
        ]);

        try {
            $changed = $service->apply($experience, $accepted);
        } catch (\Throwable $e) {
            Log::error('AI structure apply failed', ['experience_id' => $experienceId, 'error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $changedCount = count($changed['experience']) + count($changed['center']);
        Log::info('AI structure apply result', ['experience_id' => $experienceId, 'changed' => $changed]);

        if ($changedCount === 0) {
            return response()->json([
                'error' => 'The request reached the server but carried no field changes, so nothing was saved. '
                    . 'Re-open the panel and try again; if it keeps happening, check the browser Network tab for the apply request body.',
            ], 422);
        }

        return response()->json(['success' => true, 'changed' => $changed, 'changed_count' => $changedCount]);
    }
}
