<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expertise extends Model {

    protected $table = "expertise";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Expertise
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "name";
        $order = (@$param['order']) ? : "ASC";
        $objExpertise = Expertise::query();
        if (@$param['select']) {
            $objExpertise = $objExpertise->select($param['select']);
        }
        if (@$param['where']) {
            $objExpertise = $objExpertise->where($param['where']);
        }
        if (@$param['limit']) {
            $objExpertise = $objExpertise->take($param['limit']);
        }
        $resExpertise = $objExpertise->orderBy($orderby, $order)->get();
        return $resExpertise;
    }

}
