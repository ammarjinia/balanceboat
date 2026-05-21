<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherImageGallery extends Model {

    protected $table = "teacher_image_gallery";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Teacher Image Gallery
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "id";
        $order = (@$param['order']) ? : "desc";
        $objTeacherImageGallery = TeacherImageGallery::query();
        if (@$param['select']) {
            $objTeacherImageGallery = $objTeacherImageGallery->select($param['select']);
        }
        if (@$param['where']) {
            $objTeacherImageGallery = $objTeacherImageGallery->where($param['where']);
        }
        if (@$param['limit']) {
            $objTeacherImageGallery = $objTeacherImageGallery->take($param['limit']);
        }
        $resTeacherImageGallery = $objTeacherImageGallery->orderBy($orderby, $order)->get();
        return $resTeacherImageGallery;
    }

}
