<?php

namespace App\Services;

use App\Models\Experience;
use App\Models\ExperienceAccommodation;
use App\Models\Coupon;
use Carbon\Carbon;

class PricingEngine
{
    /**
     * Calculate total price for a booking with all discounts and taxes
     * Implements pricing hierarchy:
     * 1. Base price (by duration)
     * 2. Seasonal multiplier
     * 3. Occupancy-based discount
     * 4. Early-bird discount
     * 5. Coupon discount
     * 6. Tax calculation
     */
    public function calculateBookingPrice(
        Experience $experience,
        ExperienceAccommodation $accommodation,
        Carbon $arrivalDate,
        Carbon $departureDate,
        int $guestCount,
        ?string $couponCode = null
    ): array {
        $nights = $departureDate->diffInDays($arrivalDate);
        if ($nights <= 0) {
            $nights = 1;
        }

        // Get base price
        $basePrice = $this->getBasePriceForExperience($experience, $nights);

        // Get accommodation price
        $accommodationPrice = $this->getAccommodationPrice(
            $accommodation,
            $arrivalDate,
            $departureDate,
            $guestCount
        );

        // Get seasonal multiplier
        $seasonalMultiplier = $this->getSeasonalPricing($arrivalDate);

        // Calculate subtotal
        $subtotal = ($basePrice + $accommodationPrice) * $nights * $seasonalMultiplier;

        // Apply early bird discount
        $earlyBirdDiscount = $this->calculateEarlyBirdDiscount($experience, $arrivalDate, $subtotal);

        // Apply occupancy-based discount
        $occupancyDiscount = $this->calculateOccupancyDiscount($experience, $guestCount, $subtotal);

        // Apply coupon
        $couponDiscount = $couponCode ?
            $this->applyCoupon($couponCode, $subtotal, $experience) : 0;

        // Calculate totals
        $totalDiscount = $earlyBirdDiscount + $occupancyDiscount + $couponDiscount;
        $netAmount = $subtotal - $totalDiscount;
        $taxAmount = $this->calculateTax($experience, $netAmount);
        $finalAmount = $netAmount + $taxAmount;

        return [
            'base_price' => $basePrice,
            'accommodation_price' => $accommodationPrice,
            'nights' => $nights,
            'subtotal' => round($subtotal, 2),
            'seasonal_multiplier' => $seasonalMultiplier,
            'early_bird_discount' => round($earlyBirdDiscount, 2),
            'occupancy_discount' => round($occupancyDiscount, 2),
            'coupon_discount' => round($couponDiscount, 2),
            'total_discount' => round($totalDiscount, 2),
            'net_amount' => round($netAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'final_amount' => round($finalAmount, 2),
            'per_person_price' => round($finalAmount / max(1, $guestCount), 2),
            'currency' => $experience->currency ?? 'INR',
            'price_breakdown' => [
                'subtotal' => round($subtotal, 2),
                'discounts' => round($totalDiscount, 2),
                'tax' => round($taxAmount, 2),
            ]
        ];
    }

    private function getBasePriceForExperience(Experience $experience, int $nights): float
    {
        // Try to get from duration prices
        $durationPrice = $experience->durationPrices()
            ->where('duration', $nights)
            ->first();

        if ($durationPrice) {
            return $durationPrice->price;
        }

        // Fallback to base price
        return $experience->price_per_person ?? 0;
    }

    private function getAccommodationPrice(
        ExperienceAccommodation $accommodation,
        Carbon $arrivalDate,
        Carbon $departureDate,
        int $guestCount
    ): float {
        // Check for seasonal accommodation pricing
        $priceRecord = $accommodation->prices()
            ->where('start_date', '<=', $arrivalDate)
            ->where('end_date', '>=', $departureDate)
            ->first();

        if ($priceRecord) {
            return $priceRecord->price_per_night_per_guest;
        }

        return $accommodation->price_per_night_per_guest ?? 0;
    }

    private function getSeasonalPricing(Carbon $date): float
    {
        // Determine season based on month
        $month = $date->month;

        return match ($month) {
            1, 2, 3 => 1.0,        // Regular season
            4, 5 => 0.8,           // Off-season
            6, 7, 8, 9 => 0.7,    // Monsoon (lower rates)
            10, 11, 12 => 1.2,    // Peak season
            default => 1.0
        };
    }

    private function calculateEarlyBirdDiscount(
        Experience $experience,
        Carbon $arrivalDate,
        float $baseAmount
    ): float {
        if (!$experience->early_bird_discount || !$experience->early_bird_days) {
            return 0;
        }

        $daysUntilStart = now()->diffInDays($arrivalDate, false);

        if ($daysUntilStart < $experience->early_bird_days) {
            $discountRate = $experience->early_bird_discount / 100;
            return $baseAmount * $discountRate;
        }

        return 0;
    }

    private function calculateOccupancyDiscount(
        Experience $experience,
        int $guestCount,
        float $baseAmount
    ): float {
        // Group discounts based on guest count
        $discountPercentage = match (true) {
            $guestCount >= 10 => 15,
            $guestCount >= 6 => 10,
            $guestCount >= 4 => 5,
            default => 0
        };

        return $baseAmount * ($discountPercentage / 100);
    }

    private function applyCoupon(string $code, float $amount, Experience $experience): float
    {
        // This would query the Coupon model in a real implementation
        // For now, return 0
        return 0;
    }

    private function calculateTax(Experience $experience, float $amount): float
    {
        $taxRate = $experience->center?->commission?->tax ?? 18;
        return $amount * ($taxRate / 100);
    }
}
