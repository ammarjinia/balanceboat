<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Services\AvailabilityEngine;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function __construct(private AvailabilityEngine $availabilityEngine) {}

    public function calendar()
    {
        $experience = Experience::findOrFail(request('experience_id'));
        $accommodation = $experience->accommodations()
            ->find(request('accommodation_id'));

        $startDate = Carbon::parse(request('start_date', now()));
        $endDate = Carbon::parse(request('end_date', now()->addMonths(3)));

        $calendar = $this->availabilityEngine->getAvailabilityCalendar(
            $experience,
            $accommodation,
            $startDate,
            $endDate
        );

        return response()->json([
            'success' => true,
            'data' => $calendar,
        ]);
    }

    public function checkAvailability()
    {
        $experience = Experience::findOrFail(request('experience_id'));
        $accommodation = $experience->accommodations()
            ->findOrFail(request('accommodation_id'));

        $arrivalDate = Carbon::parse(request('arrival_date'));
        $departureDate = Carbon::parse(request('departure_date'));
        $guestCount = request('guest_count', 1);

        $isAvailable = $this->availabilityEngine->canBook(
            $experience,
            $accommodation,
            $arrivalDate,
            $departureDate,
            $guestCount
        );

        return response()->json([
            'success' => true,
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Dates are available' : 'Dates are not available',
        ]);
    }
}
