<?php

namespace App\Services;

use App\Models\Experience;
use App\Models\Center;
use App\Events\RetreatPublished;
use App\Events\RetreatDrafted;

class RetreatService
{
    public function __construct(
        private SchedulingEngine $schedulingEngine
    ) {}

    /**
     * Create new retreat
     */
    public function createRetreat(Center $center, array $data): Experience
    {
        $retreat = new Experience($data);
        $retreat->center_id = $center->id;
        $retreat->is_draft = true;
        $retreat->save();

        return $retreat;
    }

    /**
     * Update retreat
     */
    public function updateRetreat(Experience $retreat, array $data): Experience
    {
        $retreat->update($data);
        return $retreat;
    }

    /**
     * Publish retreat (make it bookable)
     */
    public function publishRetreat(Experience $retreat): bool
    {
        if (!$this->isRetreatComplete($retreat)) {
            throw new \Exception('Retreat must have all required fields before publishing');
        }

        $retreat->update(['is_draft' => false, 'is_bookable' => true]);
        event(new RetreatPublished($retreat));

        return true;
    }

    /**
     * Draft retreat (make it non-bookable)
     */
    public function draftRetreat(Experience $retreat): bool
    {
        $retreat->update(['is_draft' => true, 'is_bookable' => false]);
        event(new RetreatDrafted($retreat));

        return true;
    }

    /**
     * Delete retreat
     */
    public function deleteRetreat(Experience $retreat): bool
    {
        // Cancel all pending bookings
        $retreat->bookings()
            ->where('order_status', '!=', 'confirmed')
            ->update(['order_status' => 'cancelled']);

        return $retreat->delete();
    }

    /**
     * Duplicate retreat
     */
    public function duplicateRetreat(Experience $retreat, ?Carbon $newStartDate = null): Experience
    {
        return $this->schedulingEngine->cloneRetreat(
            $retreat,
            $newStartDate ?? $retreat->start_date_time->addMonths(1)
        );
    }

    /**
     * Check retreat completeness
     */
    private function isRetreatComplete(Experience $retreat): bool
    {
        return $retreat->name &&
            $retreat->center_id &&
            $retreat->accommodations()->exists() &&
            $retreat->price_per_person &&
            $retreat->start_date_time &&
            $retreat->end_date_time;
    }

    /**
     * Get retreat summary with metrics
     */
    public function getRetreatSummary(Experience $retreat): array
    {
        return [
            'id' => $retreat->id,
            'name' => $retreat->name,
            'status' => $retreat->is_draft ? 'draft' : 'published',
            'dates' => [
                'start' => $retreat->start_date_time?->format('Y-m-d'),
                'end' => $retreat->end_date_time?->format('Y-m-d'),
            ],
            'capacity' => $retreat->total_spaces,
            'booked' => $retreat->occupied_spaces,
            'available' => $retreat->available_spaces,
            'occupancy_percent' => $retreat->occupancy_percentage,
            'base_price' => $retreat->price_per_person,
            'bookings' => $retreat->bookings()->where('order_status', 'confirmed')->count(),
            'revenue' => $retreat->bookings()->where('payment_status', 'completed')->sum('pay_amount'),
            'rating' => $retreat->average_rating,
        ];
    }
}
