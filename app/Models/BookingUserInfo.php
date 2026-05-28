<?php

namespace App\Models;

class BookingUserInfo extends Model
{
    protected $fillable = [
        'booking_id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'message',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
