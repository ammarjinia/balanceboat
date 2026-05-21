<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CentreOnboard extends Model {

    protected $table = "centre_onboard";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

}
