<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceAccommodationImage extends Model
{
    protected $table = 'experience_accomodation_image_gallery';

    protected $fillable = [
        'experience_id',
        'accomodation_id',
        'image_title',
        'image_url',
    ];
}
