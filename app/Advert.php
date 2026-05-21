<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Advert extends Model {

    protected $table = "adverts";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

}
