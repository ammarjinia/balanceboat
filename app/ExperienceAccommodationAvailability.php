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

    /**
     * Whether a given accommodation is bookable for a given start date.
     * A date with no explicit row is treated as open/bookable — the calendar
     * only needs to be touched to close off or restrict specific dates.
     */
    public static function checkBookable(int $experienceId, int $accommodationId, string $date): array
    {
        $row = static::where('experience_id', $experienceId)
            ->where('accommodation_id', $accommodationId)
            ->where('start_date', $date)
            ->first();

        if (!$row) {
            return ['bookable' => true, 'status' => 'open', 'remaining' => null];
        }

        return [
            'bookable'  => !in_array($row->status, ['closed', 'full']),
            'status'    => $row->status,
            'remaining' => $row->remaining,
        ];
    }
}
