<?php

namespace App\Services;

use App\Models\Experience;
use App\Models\ExperienceAccommodation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AvailabilityEngine
{
    /**
     * Get availability calendar for retreat
     */
    public function getAvailabilityCalendar(
        Experience $experience,
        ?ExperienceAccommodation $accommodation = null,
        Carbon $startDate,
        Carbon $endDate
    ): array {
        $calendar = [];

        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $calendar[$date->format('Y-m-d')] = [
                'date' => $date->format('Y-m-d'),
                'is_available' => $this->isDateAvailable($experience, $date),
                'availability_count' => $this->getAvailableCount($experience, $accommodation, $date),
                'total_capacity' => $this->getTotalCapacity($experience, $accommodation),
                'booked_count' => $this->getBookedCount($experience, $accommodation, $date),
                'is_blackout' => $this->isBlackoutDate($experience, $date),
            ];
        }

        return $calendar;
    }

    private function isDateAvailable(Experience $experience, Carbon $date): bool
    {
        // Check if date falls within experience date range
        if ($experience->start_date_time && $experience->end_date_time) {
            return $date->between(
                $experience->start_date_time->startOfDay(),
                $experience->end_date_time->endOfDay()
            );
        }

        // Check if recurring and falls within recurrence pattern
        if ($experience->is_recurring) {
            return $this->isDateInRecurringPattern($experience, $date);
        }

        return true; // Anytime retreat
    }

    private function getAvailableCount(
        Experience $experience,
        ?ExperienceAccommodation $accommodation,
        Carbon $date
    ): int {
        if ($accommodation) {
            $booked = $accommodation->bookings()
                ->where('arrival_date', $date->format('Y-m-d'))
                ->where('order_status', 'confirmed')
                ->sum('guest_count') ?? 0;

            return max(0, $accommodation->max_guest_in_room - $booked);
        }

        // Get total availability across all accommodations
        $booked = 0;
        $total = 0;

        foreach ($experience->accommodations as $acc) {
            $bookedForAcc = $acc->bookings()
                ->where('arrival_date', $date->format('Y-m-d'))
                ->where('order_status', 'confirmed')
                ->sum('guest_count') ?? 0;

            $booked += $bookedForAcc;
            $total += $acc->max_guest_in_room ?? 0;
        }

        return max(0, $total - $booked);
    }

    private function getTotalCapacity(
        Experience $experience,
        ?ExperienceAccommodation $accommodation
    ): int {
        if ($accommodation) {
            return $accommodation->max_guest_in_room ?? 0;
        }

        return $experience->accommodations->sum('max_guest_in_room') ?? 0;
    }

    private function getBookedCount(
        Experience $experience,
        ?ExperienceAccommodation $accommodation,
        Carbon $date
    ): int {
        if ($accommodation) {
            return $accommodation->bookings()
                ->where('arrival_date', $date->format('Y-m-d'))
                ->where('order_status', 'confirmed')
                ->sum('guest_count') ?? 0;
        }

        $booked = 0;
        foreach ($experience->accommodations as $acc) {
            $booked += $acc->bookings()
                ->where('arrival_date', $date->format('Y-m-d'))
                ->where('order_status', 'confirmed')
                ->sum('guest_count') ?? 0;
        }

        return $booked;
    }

    private function isBlackoutDate(Experience $experience, Carbon $date): bool
    {
        // Check recurring exceptions for cancellations
        return $experience->recurringRules()
            ->where('is_cancelled', true)
            ->whereDate('start_date', $date->format('Y-m-d'))
            ->exists();
    }

    private function isDateInRecurringPattern(Experience $experience, Carbon $date): bool
    {
        $recurring = $experience->recurringRules()->first();

        if (!$recurring) {
            return false;
        }

        // Check if date is after start and before end
        if ($date->isBefore($recurring->start_date) || $date->isAfter($recurring->end_date)) {
            return false;
        }

        // Check recurrence pattern
        return $this->matchesRecurrencePattern($recurring, $date);
    }

    private function matchesRecurrencePattern($recurring, Carbon $date): bool
    {
        return match ($recurring->recurring_type) {
            'Daily' => true,
            'Weekly' => in_array(
                $date->format('l'),
                json_decode($recurring->day_of_week, true) ?? []
            ),
            'Monthly' => $date->day == $recurring->day_of_month,
            'Yearly' => $date->format('m-d') == Carbon::parse($recurring->month_of_year)->format('m-d'),
            default => false
        };
    }

    /**
     * Check if booking is possible
     */
    public function canBook(
        Experience $experience,
        ExperienceAccommodation $accommodation,
        Carbon $arrivalDate,
        Carbon $departureDate,
        int $guestCount
    ): bool {
        // Check each night
        $period = CarbonPeriod::create($arrivalDate, $departureDate);

        foreach ($period as $date) {
            $available = $this->getAvailableCount($experience, $accommodation, $date);

            if ($available < $guestCount) {
                return false;
            }
        }

        return true;
    }
}
