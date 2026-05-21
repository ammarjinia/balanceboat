<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceAccomodations extends Model {

    protected $table = "experience_accomodations";
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
        $objExperienceAccomodations = ExperienceAccomodations::query();
        if (@$param['select']) {
            $objExperienceAccomodations = $objExperienceAccomodations->select($param['select']);
        }
        if (@$param['where']) {
            $objExperienceAccomodations = $objExperienceAccomodations->where($param['where']);
        }
        if (@$param['limit']) {
            $objExperienceAccomodations = $objExperienceAccomodations->take($param['limit']);
        }
        $resExperienceAccomodations = $objExperienceAccomodations->orderBy($orderby, $order)->get();
        return $resExperienceAccomodations;
    }

}
