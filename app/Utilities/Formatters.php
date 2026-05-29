<?php

namespace App\Utilities;

class Formatters
{
    public static function price($amount, $currency = 'INR'): string
    {
        if ($currency === 'INR') {
            return '₹' . number_format($amount, 2);
        }

        return $currency . ' ' . number_format($amount, 2);
    }

    public static function percentage($value, $decimals = 0): string
    {
        return round($value, $decimals) . '%';
    }

    public static function dateRange($startDate, $endDate, $format = 'M d'): string
    {
        return $startDate->format($format) . ' - ' . $endDate->format($format);
    }

    public static function shortName($firstName, $lastName): string
    {
        return substr($firstName, 0, 1) . substr($lastName, 0, 1);
    }

    public static function phoneNumber($phone): string
    {
        $cleaned = preg_replace('/\D/', '', $phone);

        if (strlen($cleaned) === 10) {
            return '+91 ' . substr($cleaned, 0, 5) . ' ' . substr($cleaned, 5);
        }

        return $phone;
    }
}
