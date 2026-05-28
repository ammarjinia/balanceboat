<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'date_of_birth',
        'street_address',
        'city',
        'zipcode',
        'country',
        'profile_image_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
    ];

    // Relations
    public function centers()
    {
        return $this->belongsToMany(Center::class)
            ->withPivot('role', 'status')
            ->withTimestamps();
    }

    public function primary_center()
    {
        return $this->belongsTo(Center::class, 'primary_center_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Attributes
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getPrimaryCenter()
    {
        return $this->centers()->wherePivot('role', 'admin')->first();
    }

    public function hasCenter($centerId)
    {
        return $this->centers()->where('center_id', $centerId)->exists();
    }
}
