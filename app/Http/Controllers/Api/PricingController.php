<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\ExperienceAccommodation;
use App\Services\PricingEngine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PricingController extends Controller
{
    public function __construct(private PricingEngine $pricingEngine)
    {
        $this->middleware('auth:sanctum');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'experience_id' => 'required|integer|exists:experiences,id',
            'accommodation_id' => 'required|integer|exists:experience_accomodations,id',
            'arrival_date' => 'required|date|after_or_equal:today',
            'departure_date' => 'required|date|after:arrival_date',
            'guest_count' => 'required|integer|min:1|max:20',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        $experience = Experience::findOrFail($request->experience_id);
        $accommodation = $experience->accommodations()
            ->findOrFail($request->accommodation_id);

        $pricing = $this->pricingEngine->calculateBookingPrice(
            $experience,
            $accommodation,
            Carbon::parse($request->arrival_date),
            Carbon::parse($request->departure_date),
            $request->guest_count,
            $request->coupon_code
        );

        return response()->json([
            'success' => true,
            'data' => $pricing
        ]);
    }
}
