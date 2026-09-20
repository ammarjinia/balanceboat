<?php

namespace App\Console\Commands;

use App\Centers;
use Carbon\Carbon;

/**
 * Workflow 1 from the automation spec: onboarding and setup (emails 1–6).
 *
 * Runs daily. The sequence is deliberately *gated* rather than parallel — a center that has not
 * added a retreat yet is not also asked to set a calendar and review pricing on the same morning.
 * Each step only opens once the previous one is satisfied, which is the Laravel equivalent of the
 * branch-and-exit structure the spec describes for the Brevo onboarding journey.
 */
class CenterEmailsOnboarding extends AbstractCenterEmailCommand
{
    protected $signature = 'center-emails:onboarding
                            {--dry-run : Evaluate and report without queueing or sending anything}
                            {--only= : Restrict the run to a single template key}
                            {--center= : Restrict the run to one center id}';

    protected $description = 'Send center-panel onboarding and setup emails (welcome, profile, first retreat, accommodation, availability, pricing)';

    /**
     * Only centers registered on or after the cutover date.
     *
     * Without this, the first cron run would "welcome" every legacy directory listing on the
     * platform. The date is configurable so a backfill can be run deliberately if that is ever
     * wanted, rather than happening by accident.
     */
    protected function candidates()
    {
        $since = config('center_emails.onboarding_since');

        $query = parent::candidates();

        if (!$since) {
            // Null cutover means onboarding is switched off entirely.
            return $query->whereRaw('1 = 0');
        }

        return $query->where('created_at', '>=', Carbon::parse($since)->startOfDay());
    }

    protected function evaluate(Centers $center, array $snapshot): void
    {
        $t       = config('center_emails.thresholds');
        $ageDays = $snapshot['age_days'] ?? 0;

        // Email 1 — sent once, on the first run after the account appears.
        $this->queue($center, 'welcome', ['trigger' => 'CenterRegistered']);

        // Email 2 — profile still incomplete a day after signup.
        if ($ageDays >= $t['profile_reminder_after_days'] && !$snapshot['profile_complete']) {
            $this->queue($center, 'profile_incomplete', ['trigger' => 'CenterProfileIncomplete']);
        }

        // Email 3 — no retreat at all two days after signup. Nothing further in the sequence
        // makes sense until this is done, so the rest of the chain stops here.
        if ($snapshot['retreat_count'] === 0) {
            if ($ageDays >= $t['first_retreat_after_days']) {
                $this->queue($center, 'first_retreat', ['trigger' => 'FirstRetreatMissing']);
            }

            return;
        }

        // Email 4 — a retreat exists but the rooms behind it are not described.
        //
        // Sent on the first run after the retreat goes up, per "after first retreat upload"; the
        // template's 3-day cooldown is what produces the follow-up the spec asks for, rather than
        // delaying the initial ask by three days.
        if (!$snapshot['accommodation_complete']) {
            $this->queue($center, 'accommodation_missing', ['trigger' => 'AccommodationMissing']);

            return;
        }

        // Email 5 — accommodation is described, but no calendar has ever been published.
        // Distinct from the maintenance-side "your calendar is stale" reminder: this partner has
        // never set one, so the copy asks them to set it up rather than refresh it.
        if (!$snapshot['availability_updated_at']) {
            $this->queue($center, 'availability_setup', ['trigger' => 'AvailabilityNotUpdated']);

            return;
        }

        // Email 6 — the calendar is live, so pricing becomes the monthly ask.
        $pricingAge = $snapshot['pricing_updated_at']
            ? $snapshot['pricing_updated_at']->diffInDays(now())
            : null;

        if ($pricingAge === null || $pricingAge >= $t['pricing_stale_days']) {
            $this->queue($center, 'pricing_update', ['trigger' => 'PricingNotUpdated']);
        }
    }
}
