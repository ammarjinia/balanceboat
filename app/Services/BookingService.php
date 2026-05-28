<?php

namespace App\Services;

use App\Models\Experience;
use App\Models\ExperienceAccommodation;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use App\Events\BookingConfirmed;

class BookingService
{
    public function __construct(
        private PricingEngine $pricingEngine,
        private AvailabilityEngine $availabilityEngine
    ) {}

    /**
     * Create booking
     */
    public function createBooking(
        Experience $experience,
        ExperienceAccommodation $accommodation,
        User $user,
        array $bookingData
    ): Booking {
        $arrivalDate = Carbon::parse($bookingData['arrival_date']);
        $departureDate = Carbon::parse($bookingData['departure_date']);
        $guestCount = $bookingData['guest_count'];

        // Check availability
        if (!$this->availabilityEngine->canBook(
            $experience,
            $accommodation,
            $arrivalDate,
            $departureDate,
            $guestCount
        )) {
            throw new \Exception('Not enough availability for selected dates');
        }

        // Calculate pricing
        $pricing = $this->pricingEngine->calculateBookingPrice(
            $experience,
            $accommodation,
            $arrivalDate,
            $departureDate,
            $guestCount,
            $bookingData['coupon_code'] ?? null
        );

        // Create booking
        $booking = Booking::create([
            'experience_id' => $experience->id,
            'experience_accomodation_id' => $accommodation->id,
            'user_id' => $user->id,
            'arrival_date' => $arrivalDate,
            'start_date_time' => $arrivalDate->startOfDay(),
            'end_date_time' => $departureDate->endOfDay(),
            'duration' => $guestCount,
            'guest_count' => $guestCount,
            'price_per_person' => $pricing['per_person_price'],
            'booking_amount' => $pricing['subtotal'],
            'discount_amount' => $pricing['total_discount'],
            'pay_amount' => $pricing['final_amount'],
            'currency' => $experience->currency ?? 'INR',
            'order_status' => Booking::STATUS_PENDING,
            'payment_status' => Booking::PAYMENT_PENDING,
            'is_full_day_event' => $experience->is_full_day_event,
        ]);

        // Store user info
        $booking->userInfo()->create([
            'firstname' => $bookingData['first_name'],
            'lastname' => $bookingData['last_name'],
            'email' => $bookingData['email'],
            'phone' => $bookingData['phone'],
            'message' => $bookingData['message'] ?? null
        ]);

        // Store address info if provided
        if (isset($bookingData['billing_address'])) {
            $booking->addressInfo()->create([
                'billing_name' => $bookingData['billing_name'] ?? null,
                'billing_address' => $bookingData['billing_address'],
                'billing_city' => $bookingData['billing_city'] ?? null,
                'billing_state' => $bookingData['billing_state'] ?? null,
                'billing_zip' => $bookingData['billing_zip'] ?? null,
                'billing_country' => $bookingData['billing_country'] ?? null,
                'billing_tel' => $bookingData['billing_tel'] ?? null,
                'billing_email' => $bookingData['billing_email'] ?? null,
            ]);
        }

        return $booking;
    }

    /**
     * Confirm booking (after payment)
     */
    public function confirmBooking(Booking $booking, string $transactionId): bool
    {
        $booking->update([
            'order_status' => Booking::STATUS_CONFIRMED,
            'payment_status' => Booking::PAYMENT_COMPLETED,
            'transaction_id' => $transactionId
        ]);

        event(new BookingConfirmed($booking));

        return true;
    }

    /**
     * Cancel booking
     */
    public function cancelBooking(Booking $booking, ?string $reason = null): bool
    {
        if (!$booking->canBeCancelled()) {
            throw new \Exception('Booking cannot be cancelled');
        }

        $booking->update([
            'order_status' => Booking::STATUS_CANCELLED
        ]);

        // Process refund if needed
        if ($booking->payment_status === Booking::PAYMENT_COMPLETED) {
            $refundAmount = $this->calculateRefund($booking);
            // Process refund to payment gateway
        }

        return true;
    }

    private function calculateRefund(Booking $booking): float
    {
        $experience = $booking->experience;
        $cancelledDays = now()->diffInDays($booking->arrival_date);

        $policy = $experience->center?->commission;

        if (!$policy) {
            return $booking->pay_amount * 0.5; // Default 50%
        }

        if (!$policy->cancellation_policy_condition) {
            return $booking->pay_amount; // Full refund
        }

        if ($cancelledDays > $policy->cancellation_policy_days) {
            return $booking->pay_amount; // Full refund
        }

        return 0; // No refund
    }
}
