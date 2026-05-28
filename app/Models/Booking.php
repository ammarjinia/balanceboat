<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'experience_id',
        'experience_accomodation_id',
        'user_id',
        'arrival_date',
        'start_date_time',
        'end_date_time',
        'duration',
        'guest_count',
        'price_per_person',
        'booking_amount',
        'discount_amount',
        'commission_amount',
        'pay_amount',
        'currency',
        'order_status',
        'payment_status',
        'transaction_id',
        'is_full_day_event',
        'is_recurring',
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
        'is_full_day_event' => 'boolean',
        'is_recurring' => 'boolean',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_COMPLETED = 'completed';

    // Relations
    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    public function accommodation()
    {
        return $this->belongsTo(ExperienceAccommodation::class, 'experience_accomodation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionInfo()
    {
        return $this->hasOne(BookingTransactionInfo::class);
    }

    public function addressInfo()
    {
        return $this->hasOne(BookingTransactionAddressInfo::class);
    }

    public function userInfo()
    {
        return $this->hasOne(BookingUserInfo::class);
    }

    // Scopes
    public function scopeConfirmed($query)
    {
        return $query->where('order_status', self::STATUS_CONFIRMED);
    }

    public function scopePending($query)
    {
        return $query->where('order_status', self::STATUS_PENDING);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('arrival_date', [$startDate, $endDate]);
    }

    // Business Logic
    public function getNetAmountAttribute()
    {
        return $this->booking_amount - $this->discount_amount;
    }

    public function canBeCancelled()
    {
        return $this->order_status !== self::STATUS_CANCELLED &&
            $this->arrival_date?->isFuture();
    }

    public function getTotalNightsAttribute()
    {
        if ($this->start_date_time && $this->end_date_time) {
            return $this->end_date_time->diffInDays($this->start_date_time);
        }
        return (int)$this->duration;
    }
}
