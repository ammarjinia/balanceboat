<?php

namespace App\Models;

class PayoutAccount extends Model
{
    protected $fillable = [
        'center_id',
        'account_holder_name',
        'bank_name',
        'account_number',
        'ifsc_code',
        'preferred_payout_cycle',
        'upi_id',
        'is_verified',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }
}
