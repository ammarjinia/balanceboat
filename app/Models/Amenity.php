<?php

namespace App\Models;

class Amenity extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image_url'];

    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'experience_amenities');
    }
}
