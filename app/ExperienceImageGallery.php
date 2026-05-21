<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceImageGallery extends Model {

    protected $table = "experience_image_gallery";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Experience Image Gallery
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "id";
        $order = (@$param['order']) ? : "desc";
        $objExperienceImageGallery = ExperienceImageGallery::query();
        if (@$param['select']) {
            $objExperienceImageGallery = $objExperienceImageGallery->select($param['select']);
        }
        if (@$param['where']) {
            $objExperienceImageGallery = $objExperienceImageGallery->where($param['where']);
        }
        if (@$param['limit']) {
            $objExperienceImageGallery = $objExperienceImageGallery->take($param['limit']);
        }
        $resExperienceImageGallery = $objExperienceImageGallery->orderBy($orderby, $order)->get();
        return $resExperienceImageGallery;
    }

}
