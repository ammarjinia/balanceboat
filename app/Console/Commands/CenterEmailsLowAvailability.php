<?php

namespace App\Console\Commands;

use App\Centers;

/**
 * Email 11 — "Low Availability Warning". Weekly inventory check.
 *
 * Kept out of the daily maintenance run because its condition is volatile: bookings move slots
 * from open to full continuously, and a partner whose inventory hovers around the threshold would
 * otherwise be re-evaluated every morning. A weekly cadence matches the spec and gives the
 * template's 7-day cooldown something to line up with.
 */
class CenterEmailsLowAvailability extends AbstractCenterEmailCommand
{
    protected $signature = 'center-emails:low-availability
                            {--dry-run : Evaluate and report without queueing or sending anything}
                            {--only= : Restrict the run to a single template key}
                            {--center= : Restrict the run to one center id}';

    protected $description = 'Warn centers whose bookable future dates have dropped below the threshold';

    protected function candidates()
    {
        return parent::candidates()
            ->whereExists(function ($q) {
                $q->selectRaw(1)
                  ->from('experiences')
                  ->whereColumn('experiences.center_id', 'centers.id')
                  ->whereNull('experiences.deleted_at')
                  ->where('experiences.is_draft', 0);
            });
    }

    protected function evaluate(Centers $center, array $snapshot): void
    {
        $threshold = (int) config('center_emails.thresholds.low_availability_slots', 3);

        // A center with zero open slots *and* no calendar at all has never published dates —
        // that's the onboarding sequence's job, not a "you're running low" warning.
        if (!$snapshot['availability_updated_at']) {
            return;
        }

        if ($snapshot['open_slots_ahead'] < $threshold) {
            $this->queue($center, 'low_availability', ['trigger' => 'LowFutureAvailability']);
        }
    }
}
