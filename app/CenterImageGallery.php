<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CenterImageGallery extends Model {

    protected $table = "center_image_gallery";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Center Image Gallery
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "id";
        $order = (@$param['order']) ? : "desc";
        $objCenterImageGallery = CenterImageGallery::query();
        if (@$param['select']) {
            $objCenterImageGallery = $objCenterImageGallery->select($param['select']);
        }
        if (@$param['where']) {
            $objCenterImageGallery = $objCenterImageGallery->where($param['where']);
        }
        if (@$param['limit']) {
            $objCenterImageGallery = $objCenterImageGallery->take($param['limit']);
        }
        $resCenterImageGallery = $objCenterImageGallery->orderBy($orderby, $order)->get();
        return $resCenterImageGallery;
    }

}
