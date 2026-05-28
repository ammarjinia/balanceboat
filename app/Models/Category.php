<?php

namespace App\Models;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image_url', 'type'];

    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'experience_category');
    }
}
