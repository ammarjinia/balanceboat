<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class BookingExperienceInfo extends Model {

    protected $table = "booking_experience_info";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of BookingExperienceInfo
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "name";
        $order = (@$param['order']) ? : "ASC";
        $objBookingExperienceInfo = BookingExperienceInfo::query();
        if (@$param['select']) {
            $objBookingExperienceInfo = $objBookingExperienceInfo->select($param['select']);
        }
        if (@$param['where']) {
            $objBookingExperienceInfo = $objBookingExperienceInfo->where($param['where']);
        }
        if (@$param['offset']) {
            $objBookingExperienceInfo = $objBookingExperienceInfo->skip($param['offset']);
        }
        if (@$param['limit']) {
            $objBookingExperienceInfo = $objBookingExperienceInfo->take($param['limit']);
        }
        $resBookingExperienceInfo = $objBookingExperienceInfo->orderBy($orderby, $order)->get();
        return $resBookingExperienceInfo;
    }

}
