<?php

namespace App\Listeners;

use App\Events\BookingConfirmed;
use App\Notifications\BookingConfirmedNotification;

class SendBookingConfirmationNotification
{
    public function handle(BookingConfirmed $event)
    {
        $booking = $event->booking;

        // Notify guest
        $booking->user->notify(new BookingConfirmedNotification($booking));

        // Notify center admins
        foreach ($booking->experience->center->users as $user) {
            $user->notify(new BookingConfirmedNotification($booking));
        }
    }
}
