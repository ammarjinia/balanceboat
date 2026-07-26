<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceView extends Model {

    protected $table = 'experience_views';

    protected $fillable = [
        'experience_id', 'visitor_hash', 'viewed_date', 'country_code', 'country_name',
    ];

    public function experience()
    {
        return $this->belongsTo('App\Experiences', 'experience_id')->select('id', 'name', 'center_id');
    }

}
