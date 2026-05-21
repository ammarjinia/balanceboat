<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model {

    protected $table = "currency";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

}
