<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogImageGallery extends Model {

    protected $table = "blog_image_gallery";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Blog Image Gallery
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "id";
        $order = (@$param['order']) ? : "desc";
        $objBlogImageGallery = BlogImageGallery::query();
        if (@$param['select']) {
            $objBlogImageGallery = $objBlogImageGallery->select($param['select']);
        }
        if (@$param['where']) {
            $objBlogImageGallery = $objBlogImageGallery->where($param['where']);
        }
        if (@$param['limit']) {
            $objBlogImageGallery = $objBlogImageGallery->take($param['limit']);
        }
        $resBlogImageGallery = $objBlogImageGallery->orderBy($orderby, $order)->get();
        return $resBlogImageGallery;
    }

}
