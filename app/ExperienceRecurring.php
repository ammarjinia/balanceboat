<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceRecurring extends Model {

    protected $table = "experience_recurring";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Experience Recurring
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "id";
        $order = (@$param['order']) ? : "DESC";
        $objExperienceRecurring = ExperienceRecurring::query();
        if (@$param['select']) {
            $objExperienceRecurring = $objExperienceRecurring->select($param['select']);
        }
        if (@$param['where']) {
            $objExperienceRecurring = $objExperienceRecurring->where($param['where']);
        }
        if (@$param['limit']) {
            $objExperienceRecurring = $objExperienceRecurring->take($param['limit']);
        }
        $resExperienceRecurring = $objExperienceRecurring->orderBy($orderby, $order)->get();
        return $resExperienceRecurring;
    }

}
