<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class BookingTransactionInfo extends Model {

    protected $table = "booking_transaction_info";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

}
