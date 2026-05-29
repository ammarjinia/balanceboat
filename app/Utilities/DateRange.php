<?php

namespace App\Utilities;

use Carbon\Carbon;

class DateRange
{
    public static function isWithinRange(Carbon $date, Carbon $start, Carbon $end): bool
    {
        return $date->between($start, $end);
    }

    public static function getDaysBetween(Carbon $start, Carbon $end): int
    {
        return $end->diffInDays($start) + 1;
    }

    public static function isInPeakSeason(Carbon $date): bool
    {
        $month = $date->month;
        return in_array($month, [1, 2, 3, 10, 11, 12]);
    }

    public static function isInMonsoon(Carbon $date): bool
    {
        $month = $date->month;
        return in_array($month, [6, 7, 8, 9]);
    }

    public static function getSeasonName(Carbon $date): string
    {
        $month = $date->month;

        return match ($month) {
            1, 2, 3 => 'Winter',
            4, 5 => 'Summer',
            6, 7, 8, 9 => 'Monsoon',
            10, 11, 12 => 'Autumn',
            default => 'Unknown'
        };
    }
}
