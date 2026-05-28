<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBookingPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public string $transactionId,
        public float $amount
    ) {}

    public function handle(BookingService $bookingService)
    {
        try {
            // Verify payment with gateway
            $verified = $this->verifyPayment($this->transactionId, $this->amount);

            if ($verified) {
                $bookingService->confirmBooking($this->booking, $this->transactionId);
            }
        } catch (\Exception $e) {
            \Log::error("Payment processing failed for booking {$this->booking->id}: " . $e->getMessage());
            $this->fail($e);
        }
    }

    private function verifyPayment(string $transactionId, float $amount): bool
    {
        // Implement payment gateway verification
        // Example: Razorpay, Stripe, etc.
        return true; // Placeholder
    }
}
