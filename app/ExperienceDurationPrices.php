<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceDurationPrices extends Model {

    protected $table = "experience_duration_prices";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];
}
