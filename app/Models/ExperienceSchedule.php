<?php

namespace App\Models;

class ExperienceSchedule extends Model
{
    protected $fillable = [
        'experience_id',
        'schedule_day',
        'schedule_start_time',
        'schedule_end_time',
        'activity_description',
    ];

    protected $casts = [
        'schedule_start_time' => 'datetime',
        'schedule_end_time' => 'datetime',
    ];

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }
}
