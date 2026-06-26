<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExperienceAccommodationAvailability extends Model
{
    protected $table = 'experience_accommodation_availability';

    protected $guarded = ['id'];

    protected $casts = [
        'start_date'   => 'date',
        'total_rooms'  => 'integer',
        'booked_rooms' => 'integer',
    ];

    public function getRemainingAttribute(): int
    {
        return max(0, $this->total_rooms - $this->booked_rooms);
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'open'     => 'Open',
            'few_left' => 'Few Left',
            'full'     => 'Full',
            'closed'   => 'Closed',
            default    => 'Unknown',
        };
    }

    public static function statusList(): array
    {
        return [
            'open'     => 'Open',
            'few_left' => 'Few Left',
            'full'     => 'Full',
            'closed'   => 'Closed',
        ];
    }

    /**
     * Auto-derive status from room counts.
     * Returns 'open' if remaining > 20% of total, 'few_left' if <= 20%, 'full' if 0.
     */
    public static function deriveStatus(int $total, int $booked): string
    {
        if ($total <= 0) return 'open';
        $remaining = max(0, $total - $booked);
        if ($remaining === 0) return 'full';
        if ($remaining / $total <= 0.20) return 'few_left';
        return 'open';
    }
}
