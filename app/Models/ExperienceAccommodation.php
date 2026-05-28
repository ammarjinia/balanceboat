<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceAccommodation extends Model
{
    protected $table = 'experience_accomodations';

    protected $fillable = [
        'experience_id',
        'accommodation_id',
        'title',
        'about',
        'price_per_night_per_guest',
        'currency',
        'max_guest_in_room',
        'accommodation_default',
    ];

    protected $casts = [
        'accommodation_default' => 'boolean',
    ];

    // Relations
    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function prices()
    {
        return $this->hasMany(ExperienceAccommodationPrice::class, 'accomodation_id');
    }

    public function galleries()
    {
        return $this->hasMany(ExperienceAccommodationImage::class, 'accomodation_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'experience_accomodation_id');
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('accommodation_default', true);
    }

    // Business Logic
    public function getAvailableSpacesForDate($date)
    {
        $booked = $this->bookings()
            ->where('arrival_date', $date)
            ->where('order_status', 'confirmed')
            ->sum('guest_count') ?? 0;

        return max(0, $this->max_guest_in_room - $booked);
    }

    public function getPriceForDateAndOccupancy($date, $occupancy = 1)
    {
        $priceRule = $this->prices()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

        return $priceRule?->price_per_night_per_guest ?? $this->price_per_night_per_guest ?? 0;
    }
}
