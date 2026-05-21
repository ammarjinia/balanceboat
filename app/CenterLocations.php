<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CenterLocations extends Model {

    protected $table = "center_locations";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Center Locations
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "id";
        $order = (@$param['order']) ? : "DESC";
        $objCenterLocations = CenterLocations::query();
        if (@$param['select']) {
            $objCenterLocations = $objCenterLocations->select($param['select']);
        }
        if (@$param['where']) {
            $objCenterLocations = $objCenterLocations->where($param['where']);
        }
        if (@$param['limit']) {
            $objCenterLocations = $objCenterLocations->take($param['limit']);
        }
        $resCenterLocations = $objCenterLocations->orderBy($orderby, $order)->get();
        return $resCenterLocations;
    }

}
