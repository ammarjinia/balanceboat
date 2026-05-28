<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    
    protected $fillable = ['name', 'slug', 'description', 'image_url', 'type'];

    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'experience_category');
    }
}
