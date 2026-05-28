<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceImage extends Model
{
    protected $table = 'experience_image_gallery';

    protected $fillable = [
        'experience_id',
        'image_title',
        'image_url',
    ];

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }
}
