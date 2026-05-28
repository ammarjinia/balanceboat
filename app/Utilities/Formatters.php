<?php

namespace App\Utilities;

class Formatters
{
    public static function price($amount, $currency = 'INR'): string
    {
        return match ($currency) {
            'INR' => '₹' . number_format($amount, 2),
            'USD' => '$' . number_format($amount, 2),
            'EUR' => '€' . number_format($amount, 2),
            'GBP' => '£' . number_format($amount, 2),
            default => number_format($amount, 2),
        };
    }

    public static function percentage($value, $total): string
    {
        if ($total === 0) return '0%';
        return round(($value / $total) * 100, 2) . '%';
    }

    public static function dateRange($startDate, $endDate): string
    {
        return $startDate->format('M d') . ' - ' . $endDate->format('M d, Y');
    }

    public static function shortName($firstName, $lastName): string
    {
        return substr($firstName, 0, 1) . substr($lastName, 0, 1);
    }
}
