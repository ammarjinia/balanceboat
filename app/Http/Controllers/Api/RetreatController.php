<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Services\SchedulingEngine;

class RetreatController extends Controller
{
    public function __construct(private SchedulingEngine $schedulingEngine) {}

    public function summary(Experience $retreat)
    {
        $summary = [
            'id' => $retreat->id,
            'name' => $retreat->name,
            'slug' => $retreat->slug,
            'price' => $retreat->price_per_person,
            'currency' => $retreat->currency,
            'duration_days' => $retreat->duration_in_days,
            'start_date' => $retreat->start_date_time?->format('Y-m-d'),
            'end_date' => $retreat->end_date_time?->format('Y-m-d'),
            'about' => $retreat->experience_summary,
            'capacity' => $retreat->total_spaces,
            'available' => $retreat->available_spaces,
            'booked' => $retreat->occupied_spaces,
            'occupancy_percent' => $retreat->occupancy_percentage,
            'rating' => $retreat->average_rating,
            'accommodations' => $retreat->accommodations()->get()->map(fn($a) => [
                'id' => $a->id,
                'title' => $a->title,
                'price' => $a->price_per_night_per_guest,
                'max_guests' => $a->max_guest_in_room,
            ]),
            'teachers' => $retreat->teachers()->get()->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'bio' => $t->short_description,
            ]),
        ];

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }

    public function availability(Experience $retreat)
    {
        $calendar = $this->schedulingEngine->generateRecurringDates($retreat);

        return response()->json([
            'success' => true,
            'data' => $calendar,
        ]);
    }
}
