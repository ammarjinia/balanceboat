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
        return $end->diffInDays($start);
    }

    public static function getMonthsBetween(Carbon $start, Carbon $end): int
    {
        return $end->diffInMonths($start);
    }

    public static function isInPeakSeason(Carbon $date): bool
    {
        $month = $date->month;
        return in_array($month, [10, 11, 12, 1, 2, 3]);
    }

    public static function isInMonsooon(Carbon $date): bool
    {
        $month = $date->month;
        return in_array($month, [6, 7, 8, 9]);
    }
}
