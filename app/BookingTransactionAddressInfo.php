<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class BookingTransactionAddressInfo extends Model {

    protected $table = "booking_transaction_address_info";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

}
