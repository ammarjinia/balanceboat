<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $center = $user->primary_center ?? $user->centers()->first();

        if (!$center) {
            return view('dashboard.no-center');
        }

        $experiences = $center->experiences()->with('bookings')->get();

        $stats = [
            'total_retreats' => $experiences->count(),
            'published_retreats' => $experiences->where('is_bookable', true)->count(),
            'draft_retreats' => $experiences->where('is_draft', true)->count(),
            'total_bookings' => $experiences->sum(fn($e) => $e->bookings->where('order_status', 'confirmed')->count()),
            'total_revenue' => $experiences->sum(fn($e) => $e->bookings->where('payment_status', 'completed')->sum('pay_amount')),
            'average_rating' => $center->average_rating,
            'total_reviews' => $center->reviews()->count(),
            'capacity_utilization' => $this->calculateCapacityUtilization($experiences),
        ];

        return view('dashboard.index', [
            'center' => $center,
            'stats' => $stats,
            'recent_bookings' => $center->experiences()
                ->with('bookings.user')
                ->get()
                ->map(fn($e) => $e->bookings)
                ->flatten()
                ->sortByDesc('created_at')
                ->take(5),
            'upcoming_retreats' => $experiences
                ->where('start_date_time', '>', now())
                ->sortBy('start_date_time')
                ->take(5),
        ]);
    }

    private function calculateCapacityUtilization($experiences): float
    {
        $totalCapacity = $experiences->sum('total_spaces');
        if ($totalCapacity === 0) return 0;

        $occupied = $experiences->sum('occupied_spaces');
        return round(($occupied / $totalCapacity) * 100, 2);
    }
}
