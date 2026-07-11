<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model {

    protected $fillable = [
        'name', 'lastname', 'email', 'country_code', 'phone', 'message', 'experience_id', 'conversation_id','source','ref_url',
        'stage', 'deal_value', 'loss_reason', 'note'
    ];

    /**
     * Get the experience
     */
    public function experience()
    {
        return $this->belongsTo('App\Experiences','experience_id')->select('id','name','center_id');
    }

}
