<?php

namespace App\Models;

class Review extends Model
{
    protected $fillable = [
        'experience_id',
        'center_id',
        'user_id',
        'booking_id',
        'rating',
        'title',
        'content',
        'source',
        'verified',
    ];

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
