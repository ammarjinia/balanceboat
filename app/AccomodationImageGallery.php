<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccomodationImageGallery extends Model {

    protected $table = "accomodation_image_gallery";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Accomodation Image Gallery
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "id";
        $order = (@$param['order']) ? : "desc";
        $objAccomodationImageGallery = AccomodationImageGallery::query();
        if (@$param['select']) {
            $objAccomodationImageGallery = $objAccomodationImageGallery->select($param['select']);
        }
        if (@$param['where']) {
            $objAccomodationImageGallery = $objAccomodationImageGallery->where($param['where']);
        }
        if (@$param['limit']) {
            $objAccomodationImageGallery = $objAccomodationImageGallery->take($param['limit']);
        }
        $resAccomodationImageGallery = $objAccomodationImageGallery->orderBy($orderby, $order)->get();
        return $resAccomodationImageGallery;
    }

}
