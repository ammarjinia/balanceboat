<?php

namespace App\Http\Controllers\Retreat;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Http\Requests\StorePricingRequest;
use App\Services\PricingEngine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RetreatPricingController extends Controller
{
    public function __construct(private PricingEngine $pricingEngine)
    {
        $this->middleware('auth');
    }

    public function index(Experience $retreat)
    {
        $this->authorize('view', $retreat);

        return view('retreat.pricing', [
            'retreat' => $retreat,
            'duration_prices' => $retreat->durationPrices()->get(),
            'accommodation_prices' => $retreat->accommodations()
                ->with('prices')
                ->get(),
        ]);
    }

    public function store(StorePricingRequest $request, Experience $retreat)
    {
        $this->authorize('update', $retreat);

        $pricingType = $request->pricing_type;

        match ($pricingType) {
            'duration' => $retreat->durationPrices()->updateOrCreate(
                ['duration' => $request->duration],
                [
                    'price' => $request->price,
                    'promo_price' => $request->promo_price,
                    'currency' => $request->currency,
                ]
            ),
            'occupancy' => $retreat->accommodations()
                ->find($request->accommodation_id)
                ->prices()
                ->create([
                    'experience_id' => $retreat->id,
                    'accomodation_id' => $request->accommodation_id,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'price_per_night_per_guest' => $request->price,
                    'currency' => $request->currency,
                ]),
            default => null,
        };

        return back()->with('success', 'Pricing updated successfully');
    }

    public function calculatePrice(Request $request, Experience $retreat)
    {
        $request->validate([
            'accommodation_id' => 'required|integer',
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date',
            'guest_count' => 'required|integer|min:1',
            'coupon_code' => 'nullable|string',
        ]);

        $accommodation = $retreat->accommodations()
            ->find($request->accommodation_id);

        if (!$accommodation) {
            return response()->json(['error' => 'Accommodation not found'], 404);
        }

        $pricing = $this->pricingEngine->calculateBookingPrice(
            $retreat,
            $accommodation,
            Carbon::parse($request->arrival_date),
            Carbon::parse($request->departure_date),
            $request->guest_count,
            $request->coupon_code
        );

        return response()->json($pricing);
    }
}
