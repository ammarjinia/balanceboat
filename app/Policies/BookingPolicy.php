<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Booking;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        // Center admin can view bookings for their center
        if ($user->hasCenter($booking->experience->center_id)) {
            return true;
        }

        // Booking user can view their own booking
        return $booking->user_id === $user->id;
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->hasCenter($booking->experience->center_id);
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $user->hasCenter($booking->experience->center_id) ||
            $booking->user_id === $user->id;
    }
}
