<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingUserInfo extends Model
{
    protected $table = 'booking_user_info';
    
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
