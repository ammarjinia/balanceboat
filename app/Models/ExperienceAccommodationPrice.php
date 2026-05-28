<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceAccommodationPrice extends Model
{
    protected $table = 'experience_accomodation_prices';

    protected $fillable = [
        'experience_id',
        'accomodation_id',
        'duration',
        'start_date',
        'end_date',
        'avg_price',
        'price_per_night_per_guest',
        'promotional_price',
        'promotional_discount',
        'currency',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    public function accommodation()
    {
        return $this->belongsTo(ExperienceAccommodation::class, 'accomodation_id');
    }
}
