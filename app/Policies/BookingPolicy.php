<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Booking;

class BookingPolicy
{
    public function view(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id ||
            $user->centers()->where('center_id', $booking->experience->center_id)->exists();
    }

    public function cancel(User $user, Booking $booking)
    {
        return $this->view($user, $booking);
    }
}
