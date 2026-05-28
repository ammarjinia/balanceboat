<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CenterCommission extends Model
{
    protected $fillable = [
        'center_id',
        'commission',
        'deposit_policy',
        'deposit_amount',
        'cancellation_policy_condition',
        'cancellation_policy_days',
        'rest_of_payment',
        'rest_of_payment_days',
        'tax',
        'duration',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }
}
