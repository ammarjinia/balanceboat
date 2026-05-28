<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceRecurring extends Model
{
    protected $fillable = [
        'experience_id',
        'recurring_type',
        'start_date',
        'end_date',
        'separation_count',
        'max_num_of_occurrances',
        'day_of_week',
        'week_of_month',
        'day_of_month',
        'month_of_year',
    ];

    const TYPE_DAILY = 'Daily';
    const TYPE_WEEKLY = 'Weekly';
    const TYPE_MONTHLY = 'Monthly';
    const TYPE_YEARLY = 'Yearly';

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }
}
