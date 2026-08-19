<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use App\Experiences;
use App\Services\RetreatContentStructuringService;
use Illuminate\Http\Request;
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
        $accepted = [
            'experience' => (array) $request->input('experience', []),
            'center' => (array) $request->input('center', []),
            'amenity_ids' => array_map('intval', (array) $request->input('amenity_ids', [])),
        ];

        try {
            $service->apply($experience, $accepted);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true]);
    }
}
