<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Services\PricingEngine;
use Carbon\Carbon;

class PricingController extends Controller
{
    public function __construct(private PricingEngine $pricingEngine) {}

    public function calculate()
    {
        $experience = Experience::findOrFail(request('experience_id'));
        $accommodation = $experience->accommodations()
            ->findOrFail(request('accommodation_id'));

        $arrivalDate = Carbon::parse(request('arrival_date'));
        $departureDate = Carbon::parse(request('departure_date'));
        $guestCount = request('guest_count', 1);

        $pricing = $this->pricingEngine->calculateBookingPrice(
            $experience,
            $accommodation,
            $arrivalDate,
            $departureDate,
            $guestCount,
            request('coupon_code')
        );

        return response()->json([
            'success' => true,
            'data' => $pricing,
        ]);
    }
}
