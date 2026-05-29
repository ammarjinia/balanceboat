<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Retreat Booking is Confirmed!')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line('Your booking for ' . $this->booking->experience->name . ' has been confirmed.')
            ->line('Arrival Date: ' . $this->booking->arrival_date->format('M d, Y'))
            ->line('Departure Date: ' . $this->booking->end_date_time->format('M d, Y'))
            ->line('Total Amount: ₹' . number_format($this->booking->pay_amount, 0))
            ->action('View Booking', route('booking.show', $this->booking))
            ->line('Thank you for booking with us!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => 'Your booking has been confirmed',
            'retreat_name' => $this->booking->experience->name,
        ];
    }
}
