<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accomodation extends Model {

    protected $table = "accomodation";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Accomodation
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "name";
        $order = (@$param['order']) ? : "ASC";
        $objAccomodation = Accomodation::query();
        if (@$param['select']) {
            $objAccomodation = $objAccomodation->select($param['select']);
        }
        if (@$param['where']) {
            $objAccomodation = $objAccomodation->where($param['where']);
        }
        if (@$param['limit']) {
            $objAccomodation = $objAccomodation->take($param['limit']);
        }
        $resAccomodation = $objAccomodation->orderBy($orderby, $order)->get();
        return $resAccomodation;
    }

}
