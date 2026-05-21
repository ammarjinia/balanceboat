<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceCategory extends Model {

    protected $table = "experience_category";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Experience Category
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "id";
        $order = (@$param['order']) ? : "DESC";
        $objExperienceCategory = ExperienceCategory::query();
        if (@$param['select']) {
            $objExperienceCategory = $objExperienceCategory->select($param['select']);
        }
        if (@$param['where']) {
            $objExperienceCategory = $objExperienceCategory->where($param['where']);
        }
        if (@$param['limit']) {
            $objExperienceCategory = $objExperienceCategory->take($param['limit']);
        }
        $resExperienceCategory = $objExperienceCategory->orderBy($orderby, $order)->get();
        return $resExperienceCategory;
    }
    
    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
