<?php

namespace App\Console\Commands;

use App\Centers;

/**
 * Workflows 2 and 4 from the automation spec: listing maintenance and reactivation
 * (emails 7, 8, 9, 12, 13, 14).
 *
 * Runs daily and re-derives every condition from live data. The per-template cooldowns in
 * config/center_emails.php are what turn a daily evaluation into the biweekly / monthly / 60-day /
 * yearly cadences the spec asks for — there is no separate biweekly cron to drift out of step, and
 * a partner who fixes the problem simply stops qualifying, which is the exit condition.
 *
 * Checks are ordered most-urgent-first, and the global weekly cap in the automation service means
 * a badly neglected listing receives the top one or two rather than all six at once.
 */
class CenterEmailsMaintenance extends AbstractCenterEmailCommand
{
    protected $signature = 'center-emails:maintenance
                            {--dry-run : Evaluate and report without queueing or sending anything}
                            {--only= : Restrict the run to a single template key}
                            {--center= : Restrict the run to one center id}';

    protected $description = 'Send center-panel listing-maintenance and reactivation emails (stale availability, new retreats, photos, inactivity, outdated listing, annual audit)';

    /**
     * Maintenance only applies to centers that have actually published something — there is
     * nothing to "keep fresh" on an empty listing, and those centers belong to the onboarding
     * sequence instead.
     */
    protected function candidates()
    {
        return parent::candidates()
            ->whereExists(function ($q) {
                $q->selectRaw(1)
                  ->from('experiences')
                  ->whereColumn('experiences.center_id', 'centers.id')
                  ->whereNull('experiences.deleted_at');
            });
    }

    protected function evaluate(Centers $center, array $snapshot): void
    {
        $t = config('center_emails.thresholds');

        // Email 12 — dormant partner. Checked first: someone who has not logged in for a month
        // will not act on a photo-gallery nudge, and the cap should spend itself on getting them
        // back into the dashboard at all.
        //
        // Note last_login_at is only written from the cutover onwards (CenterAuthController@login),
        // so a partner who has never signed in since deploy reads as null and is left alone rather
        // than being told they have been away for years.
        if ($snapshot['inactive_days'] !== null && $snapshot['inactive_days'] >= $t['inactive_login_days']) {
            $this->queue($center, 'inactive_dashboard', ['trigger' => 'CenterInactive']);
        }

        // Email 13 — nothing in the listing has moved in 60 days.
        $activityAge = $snapshot['last_activity_at']
            ? $snapshot['last_activity_at']->diffInDays(now())
            : null;

        if ($activityAge !== null && $activityAge >= $t['listing_outdated_days']) {
            $this->queue($center, 'listing_outdated', ['trigger' => 'ListingStale60Days']);
        }

        // Email 7 — calendar exists but has gone stale. Centers that never set one are handled by
        // the onboarding sequence's availability_setup, so this only fires when there is something
        // to actually refresh.
        if ($snapshot['availability_updated_at']
            && $snapshot['availability_updated_at']->diffInDays(now()) >= $t['availability_stale_days']) {
            $this->queue($center, 'availability_reminder', ['trigger' => 'AvailabilityNotUpdated']);
        }

        // Email 8 — no new retreat published in 30 days.
        if ($snapshot['last_retreat_created']
            && $snapshot['last_retreat_created']->diffInDays(now()) >= $t['no_new_retreats_days']) {
            $this->queue($center, 'new_retreats', ['trigger' => 'NoNewRetreats30Days']);
        }

        // Email 9 — no image uploaded anywhere in 60 days.
        if ($snapshot['gallery_updated_at']
            && $snapshot['gallery_updated_at']->diffInDays(now()) >= $t['gallery_stale_days']) {
            $this->queue($center, 'fresh_photos', ['trigger' => 'NoGalleryUpdate60Days']);
        }

        // Email 14 — annual audit, one year after the account was created and yearly thereafter.
        // The 365-day cooldown on the template is what makes it recur rather than fire daily.
        if (($snapshot['age_days'] ?? 0) >= $t['annual_audit_days']) {
            $this->queue($center, 'annual_health_check', ['trigger' => 'AnnualListingAuditDue']);
        }
    }
}
