<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DealExperience extends Model {

    protected $table = "deal_experience";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

}
