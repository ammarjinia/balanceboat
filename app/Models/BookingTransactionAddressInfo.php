<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTransactionAddressInfo extends Model
{
    protected $table = 'booking_transaction_address_info';

    protected $fillable = [
        'booking_id',
        'billing_name',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_country',
        'billing_tel',
        'billing_email',
        'delivery_name',
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_zip',
        'delivery_country',
        'delivery_tel',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
