<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Experience;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService)
    {}

    public function index()
    {
        $user = Auth::user();
        $center = $user->getPrimaryCenter();

        if (!$center) {
            return view('dashboard.no-center');
        }

        $bookings = $center->experiences()
            ->with('bookings')
            ->get()
            ->flatMap(fn($e) => $e->bookings)
            ->where('order_status', 'confirmed')
            ->sortByDesc('created_at')
            ->paginate(15);

        return view('booking.index', [
            'center' => $center,
            'bookings' => $bookings,
        ]);
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        return view('booking.show', [
            'booking' => $booking,
            'user_info' => $booking->userInfo,
            'transaction_info' => $booking->transactionInfo,
            'address_info' => $booking->addressInfo,
        ]);
    }

    public function preview(Experience $experience)
    {
        $accommodation = $experience->accommodations()->first();

        return view('booking.preview', [
            'experience' => $experience,
            'accommodation' => $accommodation,
        ]);
    }

    public function store(StoreBookingRequest $request, Experience $experience)
    {
        $accommodation = $experience->accommodations()
            ->where('id', $request->input('accommodation_id'))
            ->first() ?? $experience->accommodations()->first();

        $user = User::firstOrCreate(
            ['email' => $request->input('email')],
            [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'phone_number' => $request->input('phone'),
                'password' => bcrypt('temporary-password'),
            ]
        );

        try {
            $booking = $this->bookingService->createBooking(
                $experience,
                $accommodation,
                $user,
                $request->validated()
            );

            return redirect()->route('booking.payment', $booking)
                ->with('success', 'Booking created! Please complete payment.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function confirm(Booking $booking)
    {
        if ($booking->order_status === 'confirmed') {
            return redirect()->back()->with('info', 'Booking already confirmed.');
        }

        $transactionId = request('transaction_id');

        try {
            $this->bookingService->confirmBooking($booking, $transactionId);
            return redirect()->route('booking.success', $booking)
                ->with('success', 'Booking confirmed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('view', $booking);

        try {
            $this->bookingService->cancelBooking($booking, request('reason'));
            return redirect()->back()->with('success', 'Booking cancelled successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}