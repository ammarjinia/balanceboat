<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CenterCommissions extends Model {

    protected $table = "center_commissions";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

}
