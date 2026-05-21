<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceAccomodationPrices extends Model {

    protected $table = "experience_accomodation_prices";
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
        $objExperienceAccomodationPrices = ExperienceAccomodationPrices::query();
        if (@$param['select']) {
            $objExperienceAccomodationPrices = $objExperienceAccomodationPrices->select($param['select']);
        }
        if (@$param['where']) {
            $objExperienceAccomodationPrices = $objExperienceAccomodationPrices->where($param['where']);
        }
        if (@$param['limit']) {
            $objExperienceAccomodationPrices = $objExperienceAccomodationPrices->take($param['limit']);
        }
        $resExperienceAccomodationPrices = $objExperienceAccomodationPrices->orderBy($orderby, $order)->get();
        return $resExperienceAccomodationPrices;
    }

}
