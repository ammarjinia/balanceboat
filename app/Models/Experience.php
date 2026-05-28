<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experience extends Model
{
    use SoftDeletes;

    protected $table = 'experiences';

    protected $fillable = [
        'name',
        'slug',
        'center_id',
        'experience_category',
        'price_per_person',
        'currency',
        'batch_size',
        'experience_summary',
        'start_date_time',
        'end_date_time',
        'is_full_day_event',
        'is_recurring',
        'is_bookable',
        'is_draft',
        'video_url',
        'banner_image_url',
        'what_is_included',
        'what_is_not_included',
        'experience_highlights',
        'cancellation_policy',
        'deposit_policy',
        'deposit_amount',
        'early_bird_discount',
        'early_bird_days',
        'duration',
    ];

    protected $casts = [
        'is_full_day_event' => 'boolean',
        'is_recurring' => 'boolean',
        'is_bookable' => 'boolean',
        'is_draft' => 'boolean',
        'deposit_policy' => 'boolean',
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
    ];

    // Relations
    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'experience_category');
    }

    public function accommodations()
    {
        return $this->hasMany(ExperienceAccommodation::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'experience_teachers');
    }

    public function schedules()
    {
        return $this->hasMany(ExperienceSchedule::class)->orderBy('schedule_day', 'asc');
    }

    public function recurringRules()
    {
        return $this->hasMany(ExperienceRecurring::class);
    }

    public function durationPrices()
    {
        return $this->hasMany(ExperienceDurationPrice::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function galleries()
    {
        return $this->hasMany(ExperienceImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'experience_amenities');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_draft', false)->where('is_bookable', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('is_draft', true);
    }

    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    // Attributes
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getDurationInDaysAttribute()
    {
        if ($this->start_date_time && $this->end_date_time) {
            return $this->end_date_time->diffInDays($this->start_date_time) + 1;
        }
        return (int)str_replace('_days', '', $this->duration ?? 7);
    }

    public function getTotalSpacesAttribute()
    {
        return $this->accommodations->sum('max_guest_in_room');
    }

    public function getOccupiedSpacesAttribute()
    {
        return $this->bookings()
            ->where('order_status', 'confirmed')
            ->sum('guest_count') ?? 0;
    }

    public function getAvailableSpacesAttribute()
    {
        return max(0, $this->total_spaces - $this->occupied_spaces);
    }

    public function getOccupancyPercentageAttribute()
    {
        if ($this->total_spaces === 0) return 0;
        return round(($this->occupied_spaces / $this->total_spaces) * 100, 2);
    }

    public function getTotalRevenueAttribute()
    {
        return $this->bookings()
            ->where('payment_status', 'completed')
            ->sum('pay_amount') ?? 0;
    }
}
