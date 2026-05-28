<?php

namespace App\Models;

class Teacher extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'meta_title',
        'short_description',
        'complete_bio',
        'teaching_since',
        'profile_image_url',
        'expertise_id',
    ];

    public function centers()
    {
        return $this->belongsToMany(Center::class, 'center_teachers');
    }

    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'experience_teachers');
    }
}
