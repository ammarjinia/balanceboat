<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookings extends Model {

    protected $table = "bookings";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of booking
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "name";
        $order = (@$param['order']) ? : "ASC";
        $objBookings = Bookings::query();
        if (@$param['select']) {
            $objBookings = $objBookings->select($param['select']);
        }
        if (@$param['where']) {
            $objBookings = $objBookings->where($param['where']);
        }
        if (@$param['limit']) {
            $objBookings = $objBookings->take($param['limit']);
        }
        $resBookings = $objBookings->orderBy($orderby, $order)->get();
        return $resBookings;
    }
    
    
    public function userInfo()
    {
        return $this->hasOne(BookingUserInfo::class,'booking_id','id');
    }

}
