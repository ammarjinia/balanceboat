<?php

namespace App\Listeners;

use App\Events\BookingConfirmed;
use App\Notifications\BookingConfirmedNotification;

class SendBookingConfirmationNotification
{
    public function handle(BookingConfirmed $event)
    {
        $booking = $event->booking;

        // Send confirmation email to guest
        $booking->user->notify(new BookingConfirmedNotification($booking));

        // Notify center admin
        foreach ($booking->experience->center->users as $admin) {
            $admin->notify(new \App\Notifications\NewBookingNotification($booking));
        }
    }
}
