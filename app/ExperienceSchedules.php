<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceSchedules extends Model {

    protected $table = "experience_schedules";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Experience Schedules
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "name";
        $order = (@$param['order']) ? : "ASC";
        $objExperienceSchedules = ExperienceSchedules::query();
        if (@$param['select']) {
            $objExperienceSchedules = $objExperienceSchedules->select($param['select']);
        }
        if (@$param['where']) {
            $objExperienceSchedules = $objExperienceSchedules->where($param['where']);
        }
        if (@$param['limit']) {
            $objExperienceSchedules = $objExperienceSchedules->take($param['limit']);
        }
        $resExperienceSchedules = $objExperienceSchedules->orderBy($orderby, $order)->get();
        return $resExperienceSchedules;
    }

}
