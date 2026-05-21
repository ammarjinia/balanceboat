<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceTeachers extends Model {

    protected $table = "experience_teachers";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Center Teachers
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "id";
        $order = (@$param['order']) ? : "DESC";
        $objCenterTeachers = ExperienceTeachers::query();
        if (@$param['select']) {
            $objCenterTeachers = $objCenterTeachers->select($param['select']);
        }
        if (@$param['where']) {
            $objCenterTeachers = $objCenterTeachers->where($param['where']);
        }
        if (@$param['limit']) {
            $objCenterTeachers = $objCenterTeachers->take($param['limit']);
        }
        $resCenterTeachers = $objCenterTeachers->orderBy($orderby, $order)->get();
        return $resCenterTeachers;
    }

}
