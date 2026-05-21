<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centers extends Model {

    protected $table = "centers";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of centers
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "name";
        $order = (@$param['order']) ? : "ASC";
        $objCenter = Centers::query();
        if (@$param['select']) {
            $objCenter = $objCenter->select($param['select']);
        }
        if (@$param['where']) {
            $objCenter = $objCenter->where($param['where']);
        }
        if (@$param['limit']) {
            $objCenter = $objCenter->take($param['limit']);
        }
        $resCenter = $objCenter->orderBy($orderby, $order)->get();
        return $resCenter;
    }

}
