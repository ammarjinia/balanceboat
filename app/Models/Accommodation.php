<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    protected $table = 'accomodation';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'max_guest_in_room',
        'banner_image_url',
    ];

    public function galleries()
    {
        return $this->hasMany(AccommodationImageGallery::class);
    }

    public function centers()
    {
        return $this->belongsToMany(Center::class, 'center_accomodations');
    }

    public function experienceAccommodations()
    {
        return $this->hasMany(ExperienceAccommodation::class);
    }
}
