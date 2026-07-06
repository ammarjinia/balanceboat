<?php

namespace App\Http\Controllers\Center;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Centers;
use App\Experiences;

class CenterCommissionController extends Controller
{
    /**
     * Commission alignment tiers — must match the scale in commission_form.blade.php's JS matrix.
     */
    public const TIERS = [15, 20, 25, 30, 35, 40, 45];

    public function __construct()
    {
        $this->middleware('center.auth');
    }

    /**
     * List this center's retreats with their current commission alignment.
     */
    public function index()
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        $experiences = Experiences::where('center_id', $centerId)->orderBy('name')->get();

        return view('center_panel.commission', compact('center', 'experiences'));
    }

    /**
     * Show the commission alignment engine for one retreat.
     */
    public function manage($experienceId)
    {
        $centerId = Session::get('center_id');
        $center   = Centers::findOrFail($centerId);

        $experience = Experiences::where('id', $experienceId)
            ->where('center_id', $centerId)
            ->firstOrFail();

        $currentCommission = $experience->commission ? (float) $experience->commission : self::TIERS[0];

        // Snap to the nearest tier for the slider's initial position
        $closestIndex = 0;
        $closestDiff  = null;
        foreach (self::TIERS as $i => $pct) {
            $diff = abs($pct - $currentCommission);
            if ($closestDiff === null || $diff < $closestDiff) {
                $closestDiff  = $diff;
                $closestIndex = $i;
            }
        }

        return view('center_panel.commission_form', compact('center', 'experience', 'currentCommission', 'closestIndex'));
    }

    /**
     * Save the selected commission alignment for this retreat.
     */
    public function save(Request $request, $experienceId)
    {
        $centerId = Session::get('center_id');

        $experience = Experiences::where('id', $experienceId)
            ->where('center_id', $centerId)
            ->firstOrFail();

        $request->validate([
            'commission' => 'required|numeric|min:0|max:100',
        ]);

        $experience->commission = $request->input('commission');
        $experience->save();

        return redirect()->route('center-panel.commission.manage', $experienceId)
            ->with('success', 'Commission alignment updated to ' . number_format($experience->commission, 0) . '% successfully.');
    }
}
