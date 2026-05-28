<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\ExperienceAccommodation;
use App\Services\AvailabilityEngine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function __construct(private AvailabilityEngine $availabilityEngine)
    {
        $this->middleware('auth:sanctum');
    }

    public function calendar(Request $request)
    {
        $request->validate([
            'experience_id' => 'required|integer|exists:experiences,id',
            'accommodation_id' => 'nullable|integer|exists:experience_accomodations,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $experience = Experience::findOrFail($request->experience_id);
        $accommodation = $request->accommodation_id ?
            $experience->accommodations()->findOrFail($request->accommodation_id) : null;

        $calendar = $this->availabilityEngine->getAvailabilityCalendar(
            $experience,
            $accommodation,
            Carbon::parse($request->start_date),
            Carbon::parse($request->end_date)
        );

        return response()->json([
            'success' => true,
            'data' => $calendar
        ]);
    }
}
