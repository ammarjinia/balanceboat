<?php

namespace App\Services;

use App\Models\Experience;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SchedulingEngine
{
    /**
     * Generate recurring dates based on recurrence rules
     */
    public function generateRecurringDates(
        Experience $experience,
        ?int $limit = 50
    ): array {
        $recurring = $experience->recurringRules()->first();

        if (!$recurring) {
            return [];
        }

        $dates = [];
        $current = Carbon::parse($recurring->start_date);
        $endDate = Carbon::parse($recurring->end_date);
        $count = 0;

        while ($current <= $endDate && $count < $limit) {
            if ($this->isValidRecurringDate($recurring, $current)) {
                $duration = $experience->duration_in_days ?? 7;

                $dates[] = [
                    'start_date' => $current->clone()->format('Y-m-d'),
                    'end_date' => $current->clone()->addDays($duration - 1)->format('Y-m-d'),
                    'is_booked' => false
                ];
                $count++;
            }

            $current = $this->getNextRecurringDate($recurring, $current);
        }

        return $dates;
    }

    private function isValidRecurringDate($recurring, Carbon $date): bool
    {
        $exceptions = $recurring->experience
            ->recurringRules()
            ->where('is_cancelled', true)
            ->pluck('start_date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        if (in_array($date->format('Y-m-d'), $exceptions)) {
            return false;
        }

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

    private function getNextRecurringDate($recurring, Carbon $current): Carbon
    {
        return match ($recurring->recurring_type) {
            'Daily' => $current->addDays($recurring->separation_count ?? 1),
            'Weekly' => $current->addWeeks($recurring->separation_count ?? 1),
            'Monthly' => $current->addMonths($recurring->separation_count ?? 1),
            'Yearly' => $current->addYears($recurring->separation_count ?? 1),
            default => $current->addDays(1)
        };
    }

    /**
     * Get day-wise itinerary
     */
    public function getItinerary(Experience $experience): array
    {
        return $experience->schedules()
            ->orderBy('schedule_day')
            ->get()
            ->map(fn($schedule) => [
                'day' => $schedule->schedule_day,
                'start_time' => $schedule->schedule_start_time?->format('H:i'),
                'end_time' => $schedule->schedule_end_time?->format('H:i'),
                'activity' => $schedule->activity_description
            ])
            ->toArray();
    }

    /**
     * Clone retreat with new dates
     */
    public function cloneRetreat(Experience $original, Carbon $newStartDate): Experience
    {
        $duration = $original->duration_in_days ?? 7;

        $clone = $original->replicate(['id']);
        $clone->start_date_time = $newStartDate;
        $clone->end_date_time = $newStartDate->addDays($duration - 1);
        $clone->is_draft = true;
        $clone->save();

        // Clone accommodations
        foreach ($original->accommodations as $accommodation) {
            $clone->accommodations()->create([
                'accommodation_id' => $accommodation->accommodation_id,
                'title' => $accommodation->title,
                'about' => $accommodation->about,
                'price_per_night_per_guest' => $accommodation->price_per_night_per_guest,
                'currency' => $accommodation->currency,
                'max_guest_in_room' => $accommodation->max_guest_in_room,
                'accommodation_default' => $accommodation->accommodation_default,
            ]);
        }

        // Clone schedules
        foreach ($original->schedules as $schedule) {
            $clone->schedules()->create([
                'schedule_day' => $schedule->schedule_day,
                'schedule_start_time' => $schedule->schedule_start_time,
                'schedule_end_time' => $schedule->schedule_end_time,
                'activity_description' => $schedule->activity_description,
            ]);
        }

        // Clone pricing
        foreach ($original->durationPrices as $price) {
            $clone->durationPrices()->create([
                'duration' => $price->duration,
                'price' => $price->price,
                'promo_price' => $price->promo_price,
                'currency' => $price->currency,
            ]);
        }

        return $clone;
    }
}
