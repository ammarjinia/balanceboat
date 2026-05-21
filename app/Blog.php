<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model {

    protected $table = "blogs";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Blog
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "name";
        $order = (@$param['order']) ? : "ASC";
        $objBlog = Blog::query();
        if (@$param['select']) {
            $objBlog = $objBlog->select($param['select']);
        }
        if (@$param['where']) {
            $objBlog = $objBlog->where($param['where']);
        }
        if (@$param['limit']) {
            $objBlog = $objBlog->take($param['limit']);
        }
        $resBlog = $objBlog->orderBy($orderby, $order)->get();
        return $resBlog;
    }

}
