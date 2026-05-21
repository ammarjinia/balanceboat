<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    
    protected $table = "category";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];
    
    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of category
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ?: "name";
        $order = (@$param['order']) ?: "ASC";
		$objCategory = Category::query();
        if (@$param['select']) {
            $objCategory = $objCategory->select($param['select']);
        }
        if (@$param['where']) {
            $objCategory = $objCategory->where($param['where']);
        }
		if (@$param['limit']) {
            $objCategory = $objCategory->take($param['limit']);
        }
        $resCategory = $objCategory->orderBy($orderby, $order)->get();
        return $resCategory;
    }

}
