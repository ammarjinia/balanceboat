<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deals extends Model {

    protected $table = "deals";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];
}
