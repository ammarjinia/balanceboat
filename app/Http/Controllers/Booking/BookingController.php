<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Experience;
use App\Models\User;
use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;
use App\Services\PricingEngine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private PricingEngine $pricingEngine
    ) {
        $this->middleware('auth')->except(['preview']);
    }

    public function index()
    {
        $center = auth()->user()->primary_center ?? auth()->user()->centers()->first();

        $experienceIds = $center->experiences()->pluck('id');

        $bookings = Booking::with('user')
            ->whereIn('experience_id', $experienceIds)
            ->orderByDesc('created_at')
            ->paginate(20);

        $totalRevenue = Booking::whereIn('experience_id', $center->experiences->pluck('id'))
            ->where('payment_status', 'completed')
            ->sum('pay_amount');

        $confirmedCount = Booking::whereIn('experience_id', $center->experiences->pluck('id'))
            ->where('order_status', 'confirmed')
            ->count();

        return view('booking.index', [
            'bookings' => $bookings,
            'total_revenue' => $totalRevenue,
            'confirmed_count' => $confirmedCount,
        ]);
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        return view('booking.show', [
            'booking' => $booking->load([
                'experience',
                'accommodation',
                'user',
                'userInfo',
                'addressInfo',
                'transactionInfo'
            ]),
        ]);
    }

    public function preview(Experience $retreat, Request $request)
    {
        $request->validate([
            'accommodation_id' => 'required|integer',
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date',
            'guest_count' => 'required|integer|min:1',
        ]);

        $accommodation = $retreat->accommodations()->find($request->accommodation_id);

        if (!$accommodation) {
            return abort(404);
        }

        $pricing = $this->pricingEngine->calculateBookingPrice(
            $retreat,
            $accommodation,
            Carbon::parse($request->arrival_date),
            Carbon::parse($request->departure_date),
            $request->guest_count,
            $request->coupon_code ?? null
        );

        return view('booking.preview', [
            'retreat' => $retreat,
            'accommodation' => $accommodation,
            'pricing' => $pricing,
            'arrival_date' => $request->arrival_date,
            'departure_date' => $request->departure_date,
            'guest_count' => $request->guest_count,
        ]);
    }

    public function store(StoreBookingRequest $request)
    {
        $experience = Experience::findOrFail($request->experience_id);
        $accommodation = $experience->accommodations()->findOrFail($request->accommodation_id);

        try {
            // Get or create user
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone_number' => $request->phone,
                    'password' => bcrypt(uniqid()),
                ]
            );

            $booking = $this->bookingService->createBooking(
                $experience,
                $accommodation,
                $user,
                $request->validated()
            );

            return redirect()
                ->route('booking.payment', $booking)
                ->with('success', 'Booking created. Please complete payment.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function confirm(Booking $booking, Request $request)
    {
        $this->authorize('update', $booking);

        $request->validate([
            'transaction_id' => 'required|string',
        ]);

        $this->bookingService->confirmBooking($booking, $request->transaction_id);

        return back()->with('success', 'Booking confirmed successfully');
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('update', $booking);

        try {
            $this->bookingService->cancelBooking($booking);
            return back()->with('success', 'Booking cancelled');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
