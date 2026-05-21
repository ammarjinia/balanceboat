<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CenterAccomodations extends Model {

    protected $table = "center_accomodations";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Center Accomodations
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "id";
        $order = (@$param['order']) ? : "DESC";
        $objCenterAccomodations = CenterAccomodations::query();
        if (@$param['select']) {
            $objCenterAccomodations = $objCenterAccomodations->select($param['select']);
        }
        if (@$param['where']) {
            $objCenterAccomodations = $objCenterAccomodations->where($param['where']);
        }
        if (@$param['limit']) {
            $objCenterAccomodations = $objCenterAccomodations->take($param['limit']);
        }
        $resCenterAccomodations = $objCenterAccomodations->orderBy($orderby, $order)->get();
        return $resCenterAccomodations;
    }

}
