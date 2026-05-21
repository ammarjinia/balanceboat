<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceRecurringManually extends Model {

    protected $table = "experience_recurring_manually";
    protected $primaryKey = 'id';
    protected $dates = ['created_at'];
    protected $guarded = [
        'id'
    ];

    /**
     * @param  array|null $param
     * @return mixed Fetch  Details of Experience Recurring Manually
     */
    public static function get_data($param = array()) {
        $orderby = (@$param['orderby']) ? : "id";
        $order = (@$param['order']) ? : "DESC";
        $objExperienceRecurringManually = ExperienceRecurringManually::query();
        if (@$param['select']) {
            $objExperienceRecurringManually = $objExperienceRecurringManually->select($param['select']);
        }
        if (@$param['where']) {
            $objExperienceRecurringManually = $objExperienceRecurringManually->where($param['where']);
        }
        if (@$param['limit']) {
            $objExperienceRecurringManually = $objExperienceRecurringManually->take($param['limit']);
        }
        $resExperienceRecurringManually = $objExperienceRecurringManually->orderBy($orderby, $order)->get();
        return $resExperienceRecurringManually;
    }

}
