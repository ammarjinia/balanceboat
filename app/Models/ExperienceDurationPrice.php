<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceDurationPrice extends Model
{
    protected $fillable = [
        'experience_id',
        'duration',
        'price',
        'promo_price',
        'currency',
    ];

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }
}
