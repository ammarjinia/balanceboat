<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image_url'];

    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'experience_amenities');
    }
}
