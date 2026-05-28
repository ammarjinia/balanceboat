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

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting("Booking Confirmed!")
            ->line("Your booking for {$this->booking->experience->name} is confirmed.")
            ->line("Arrival Date: {$this->booking->arrival_date->format('M d, Y')}")
            ->line("Total Amount: ₹" . number_format($this->booking->pay_amount))
            ->action('View Booking', route('booking.show', $this->booking))
            ->line('Thank you for choosing BalanceBoat!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'experience_name' => $this->booking->experience->name,
            'amount' => $this->booking->pay_amount,
        ];
    }
}
