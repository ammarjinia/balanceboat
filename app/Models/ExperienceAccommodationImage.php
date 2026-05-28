<?php

namespace App\Models;

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
