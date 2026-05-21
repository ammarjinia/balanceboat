<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class BookingUserInfo extends Model {

    protected $table = "booking_user_info";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of BookingUserInfo
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "name";
        $order = (@$param['order']) ? : "ASC";
        $objBookingUserInfo = BookingUserInfo::query();
        if (@$param['select']) {
            $objBookingUserInfo = $objBookingUserInfo->select($param['select']);
        }
        if (@$param['where']) {
            $objBookingUserInfo = $objBookingUserInfo->where($param['where']);
        }
        if (@$param['offset']) {
            $objBookingUserInfo = $objBookingUserInfo->skip($param['offset']);
        }
        if (@$param['limit']) {
            $objBookingUserInfo = $objBookingUserInfo->take($param['limit']);
        }
        $resBookingUserInfo = $objBookingUserInfo->orderBy($orderby, $order)->get();
        return $resBookingUserInfo;
    }

}
