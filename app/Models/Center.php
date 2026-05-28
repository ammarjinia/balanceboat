<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Center extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'center_type',
        'year_of_foundation',
        'founders',
        'about_center',
        'what_sets_us_apart',
        'our_philosophy',
        'our_mission',
        'center_highlights',
        'video_url',
        'banner_image_url',
        'website',
        'address_of_center',
        'city',
        'country',
        'email_address',
        'contact_number',
        'whatsapp_number',
        'facebook_url',
        'instagram_url',
        'have_accommodation',
        'is_draft',
        'user_id',
        'gst_number',
        'pan_number',
        'business_name',
    ];

    protected $casts = [
        'is_draft' => 'boolean',
    ];

    // Relations
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'status')
            ->withTimestamps();
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function accommodations()
    {
        return $this->belongsToMany(Accommodation::class, 'center_accommodations');
    }

    public function commission()
    {
        return $this->hasOne(CenterCommission::class);
    }

    public function galleries()
    {
        return $this->hasMany(CenterImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function payoutAccounts()
    {
        return $this->hasMany(PayoutAccount::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'center_teachers');
    }

    public function centerUsers()
    {
        return $this->belongsToMany(User::class)->withPivot('role', 'status');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_draft', false);
    }

    public function scopeDraft($query)
    {
        return $query->where('is_draft', true);
    }

    // Attributes
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getCompletionPercentageAttribute()
    {
        $fields = [
            'name',
            'about_center',
            'address_of_center',
            'email_address',
            'contact_number',
            'banner_image_url',
        ];

        $filled = collect($fields)
            ->filter(fn($field) => !empty($this->{$field}))
            ->count();

        return round(($filled / count($fields)) * 100);
    }

    public function getTotalRevenueAttribute()
    {
        return $this->experiences()
            ->with('bookings')
            ->get()
            ->sum(fn($e) => $e->bookings->where('payment_status', 'completed')->sum('pay_amount'));
    }

    public function getTotalBookingsAttribute()
    {
        return $this->experiences()
            ->with('bookings')
            ->get()
            ->sum(fn($e) => $e->bookings->where('order_status', 'confirmed')->count());
    }
}
