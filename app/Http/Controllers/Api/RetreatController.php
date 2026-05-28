<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Services\RetreatService;
use Illuminate\Http\Request;

class RetreatController extends Controller
{
    public function __construct(private RetreatService $retreatService)
    {
        $this->middleware('auth:sanctum');
    }

    public function summary(Experience $retreat)
    {
        return response()->json([
            'success' => true,
            'data' => $this->retreatService->getRetreatSummary($retreat)
        ]);
    }

    public function availability(Experience $retreat, Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $accommodations = $retreat->accommodations()
            ->with(['bookings' => fn($q) => $q->where('order_status', 'confirmed')])
            ->get()
            ->map(fn($acc) => [
                'id' => $acc->id,
                'title' => $acc->title,
                'capacity' => $acc->max_guest_in_room,
                'price' => $acc->price_per_night_per_guest,
                'availability' => [
                    'total_spaces' => $acc->max_guest_in_room,
                    'booked_spaces' => $acc->bookings->sum('guest_count'),
                    'available_spaces' => $acc->max_guest_in_room - $acc->bookings->sum('guest_count'),
                ]
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'retreat_id' => $retreat->id,
                'retreat_name' => $retreat->name,
                'accommodations' => $accommodations
            ]
        ]);
    }
}
