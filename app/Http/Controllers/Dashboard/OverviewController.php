<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Booking;
use App\Models\Experience;
use Illuminate\Support\Facades\Auth;

class OverviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $center = $user->getPrimaryCenter();

        if (!$center) {
            return view('dashboard.no-center');
        }

        $stats = [
            'total_retreats' => $center->experiences()->count(),
            'active_retreats' => $center->experiences()->where('is_draft', false)->count(),
            'total_bookings' => $center->experiences()->with('bookings')->get()
                ->sum(fn($e) => $e->bookings->where('order_status', 'confirmed')->count()),
            'total_revenue' => $center->experiences()->with('bookings')->get()
                ->sum(fn($e) => $e->bookings->where('payment_status', 'completed')->sum('pay_amount')),
            'pending_bookings' => $center->experiences()->with('bookings')->get()
                ->sum(fn($e) => $e->bookings->where('order_status', 'pending')->count()),
            'occupancy_percentage' => $this->calculateOccupancy($center),
        ];

        $upcomingRetreats = $center->experiences()
            ->where('is_draft', false)
            ->where('start_date_time', '>=', now())
            ->orderBy('start_date_time')
            ->take(5)
            ->get()
            ->map(fn($exp) => [
                'id' => $exp->id,
                'name' => $exp->name,
                'start_date' => $exp->start_date_time->format('M d, Y'),
                'booked' => $exp->occupied_spaces,
                'total' => $exp->total_spaces,
                'occupancy' => $exp->occupancy_percentage,
            ]);

        $recentBookings = $center->experiences()
            ->with('bookings')
            ->get()
            ->flatMap(fn($e) => $e->bookings)
            ->where('order_status', 'confirmed')
            ->sortByDesc('created_at')
            ->take(5)
            ->map(fn($booking) => [
                'id' => $booking->id,
                'guest_name' => $booking->userInfo->firstname . ' ' . $booking->userInfo->lastname,
                'retreat_name' => $booking->experience->name,
                'arrival_date' => $booking->arrival_date->format('M d'),
                'guests' => $booking->guest_count,
                'amount' => $booking->pay_amount,
            ]);

        return view('dashboard.index', [
            'center' => $center,
            'stats' => $stats,
            'upcoming_retreats' => $upcomingRetreats,
            'recent_bookings' => $recentBookings,
        ]);
    }

    private function calculateOccupancy(Center $center): float
    {
        $totalCapacity = $center->experiences()
            ->with('accommodations')
            ->get()
            ->sum(fn($exp) => $exp->total_spaces);

        $totalBooked = $center->experiences()
            ->with('bookings')
            ->get()
            ->sum(fn($exp) => $exp->occupied_spaces);

        if ($totalCapacity === 0) return 0;
        return round(($totalBooked / $totalCapacity) * 100, 2);
    }
}
