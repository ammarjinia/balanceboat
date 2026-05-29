<?php

namespace App\Http\Controllers\Retreat;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePricingRequest;
use App\Models\Experience;
use App\Models\ExperienceDurationPrice;

class RetreatPricingController extends Controller
{
    public function index(Experience $retreat)
    {
        $this->authorize('update', $retreat);

        $durationPrices = $retreat->durationPrices()->get();

        return view('retreat.pricing', [
            'retreat' => $retreat,
            'duration_prices' => $durationPrices,
        ]);
    }

    public function store(StorePricingRequest $request, Experience $retreat)
    {
        $this->authorize('update', $retreat);

        $data = $request->validated();
        $data['experience_id'] = $retreat->id;

        ExperienceDurationPrice::create($data);

        return redirect()->back()->with('success', 'Pricing rule added successfully!');
    }

    public function calculatePrice(Experience $retreat)
    {
        $arrivalDate = \Carbon\Carbon::parse(request('arrival_date'));
        $departureDate = \Carbon\Carbon::parse(request('departure_date'));
        $guestCount = request('guest_count', 1);
        $accommodationId = request('accommodation_id');

        $accommodation = $retreat->accommodations()
            ->where('experience_accomodation_id', $accommodationId)
            ->first();

        if (!$accommodation) {
            $accommodation = $retreat->accommodations()->first();
        }

        $pricing = app(\App\Services\PricingEngine::class)->calculateBookingPrice(
            $retreat,
            $accommodation,
            $arrivalDate,
            $departureDate,
            $guestCount
        );

        return response()->json($pricing);
    }
}
