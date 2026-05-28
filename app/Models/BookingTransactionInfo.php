<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTransactionInfo extends Model
{
    protected $table = 'booking_transaction_info';
    
    protected $fillable = [
        'booking_id',
        'tracking_id',
        'bank_ref_no',
        'order_status',
        'failure_message',
        'payment_mode',
        'card_name',
        'status_code',
        'status_message',
        'currency',
        'amount',
        'vault',
        'offer_type',
        'offer_code',
        'discount_value',
        'mer_amount',
        'eci_value',
        'retry',
        'response_code',
        'billing_notes',
        'trans_date',
    ];

    protected $casts = [
        'trans_date' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
