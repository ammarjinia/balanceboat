<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceFoodImageGallery extends Model {

    protected $table = "experience_food_image_gallery";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Experience Food Image Gallery
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "id";
        $order = (@$param['order']) ? : "desc";
        $objFoodImageGallery = ExperienceFoodImageGallery::query();
        if (@$param['select']) {
            $objFoodImageGallery = $objFoodImageGallery->select($param['select']);
        }
        if (@$param['where']) {
            $objFoodImageGallery = $objFoodImageGallery->where($param['where']);
        }
        if (@$param['limit']) {
            $objFoodImageGallery = $objFoodImageGallery->take($param['limit']);
        }
        $resFoodImageGallery = $objFoodImageGallery->orderBy($orderby, $order)->get();
        return $resFoodImageGallery;
    }

}
