<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceAccommodationDurationPrice extends Model
{
    protected $table = 'experience_accommodation_duration_prices';

    protected $guarded = ['id'];

    protected $casts = [
        'duration_days' => 'integer',
        'single_price'  => 'float',
        'double_price'  => 'float',
    ];
}
