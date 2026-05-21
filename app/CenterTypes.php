<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CenterTypes extends Model {

    protected $table = "center_types";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Center Types
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "name";
        $order = (@$param['order']) ? : "ASC";
        $objCenterTypes = CenterTypes::query();
        if (@$param['select']) {
            $objCenterTypes = $objCenterTypes->select($param['select']);
        }
        if (@$param['where']) {
            $objCenterTypes = $objCenterTypes->where($param['where']);
        }
        if (@$param['limit']) {
            $objCenterTypes = $objCenterTypes->take($param['limit']);
        }
        $resCenterTypes = $objCenterTypes->orderBy($orderby, $order)->get();
        return $resCenterTypes;
    }

}
