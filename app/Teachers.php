<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teachers extends Model {

    protected $table = "teachers";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of teachers
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "name";
        $order = (@$param['order']) ? : "ASC";
        $objTeacher = Teachers::query();
        if (@$param['select']) {
            $objTeacher = $objTeacher->select($param['select']);
        }
        if (@$param['where']) {
            $objTeacher = $objTeacher->where($param['where']);
        }
        if (@$param['limit']) {
            $objTeacher = $objTeacher->take($param['limit']);
        }
        $resTeacher = $objTeacher->orderBy($orderby, $order)->get();
        return $resTeacher;
    }

}
