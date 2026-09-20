<?php

namespace App\Services;

use App\CenterImageGallery;
use App\Centers;
use App\Experiences;
use App\ExperienceAccommodationAvailability;
use App\ExperienceImageGallery;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Computes the listing-health snapshot every center-email evaluator reasons over.
 *
 * This is the Laravel-side equivalent of the "recommended custom attributes in Brevo" list in the
 * automation spec (profile_complete, retreat_count, availability_updated_at, inactive_days, ...).
 * Because the orchestration lives here rather than in Brevo, the attributes are computed on demand
 * from the live tables instead of being mirrored into a third-party contact record — there is no
 * sync to drift out of date.
 *
 * Freshness columns (centers.availability_updated_at and friends) are written by the center-panel
 * controllers going forward. For centers that predate that instrumentation the column is null, so
 * each getter falls back to deriving a date from the underlying rows. The fallback is deliberately
 * conservative: when nothing can be derived either, the value stays null and the evaluators treat
 * null as "never set" rather than "infinitely stale", so legacy listings are nudged to *set* a
 * calendar rather than scolded for an out-of-date one.
 */
class CenterListingHealthService
{
    /**
     * Fields that make up the profile completeness score.
     *
     * Kept identical to CenterDashboardController::profileCompleteness() so the percentage a
     * partner reads in an email matches the one on their dashboard.
     */
    public const PROFILE_FIELDS = [
        'about_center'       => 'About the Center description',
        'what_sets_us_apart' => 'What Sets You Apart section',
        'our_philosophy'     => 'Our Philosophy statement',
        'banner_image_url'   => 'High-resolution banner image',
        'video_url'          => 'Video walkthrough URL',
        'address_of_center'  => 'Center address',
        'city'               => 'City',
        'country'            => 'Country',
        'contact_number'     => 'Contact phone number',
        'whatsapp_number'    => 'WhatsApp number',
        'founders'           => 'Founders information',
        'year_of_foundation' => 'Year of foundation',
        'amenities'          => 'Amenities selection',
        'center_highlights'  => 'Center highlights',
    ];

    /** @var array<int, array<string, mixed>> */
    private array $cache = [];

    /**
     * Full snapshot for one center.
     *
     * @return array<string, mixed>
     */
    public function snapshot(Centers $center): array
    {
        if (isset($this->cache[$center->id])) {
            return $this->cache[$center->id];
        }

        $centerId = $center->id;

        $experiences = Experiences::where('center_id', $centerId)
            ->whereNull('deleted_at')
            ->get(['id', 'name', 'is_draft', 'created_at', 'updated_at']);

        $experienceIds = $experiences->pluck('id')->all();

        [$profileScore, $profileMissing] = $this->profileCompleteness($center);

        $owner = $this->owner($center);

        $snapshot = [
            'center'    => $center,
            'owner'     => $owner,
            'center_id' => $centerId,

            'center_name' => $center->name,
            'first_name'  => $owner?->first_name ?: 'there',
            'city'        => $center->city,
            'country'     => $center->country,

            'profile_score'    => $profileScore,
            'profile_complete' => $profileScore >= 100,
            'profile_missing'  => $profileMissing,

            'retreat_count'         => $experiences->count(),
            'published_count'       => $experiences->where('is_draft', 0)->count(),
            'last_retreat_created'  => $experiences->max('created_at')
                ? Carbon::parse($experiences->max('created_at'))
                : null,

            'accommodation_count'    => $this->accommodationCount($centerId),
            'accommodation_complete' => $this->accommodationComplete($centerId),

            'availability_updated_at' => $this->availabilityUpdatedAt($center, $experienceIds),
            'pricing_updated_at'      => $this->pricingUpdatedAt($center, $experienceIds),
            'gallery_updated_at'      => $this->galleryUpdatedAt($center, $experienceIds),
            'profile_updated_at'      => $center->profile_updated_at
                ? Carbon::parse($center->profile_updated_at)
                : null,

            'open_slots_ahead' => $this->openSlotsAhead($experienceIds),

            'last_login_at' => $owner?->last_login_at ? Carbon::parse($owner->last_login_at) : null,
            'created_at'    => $center->created_at ? Carbon::parse($center->created_at) : null,
        ];

        // Carbon 3 returns a signed float from diffInDays (positive here, since both of these are
        // in the past), so cast to whole days — the thresholds and the copy both talk in days.
        $snapshot['age_days'] = $snapshot['created_at']
            ? (int) $snapshot['created_at']->diffInDays(now())
            : null;

        $snapshot['inactive_days'] = $snapshot['last_login_at']
            ? (int) $snapshot['last_login_at']->diffInDays(now())
            : null;

        // "Any meaningful update" for the 60-day stale-listing trigger. Deliberately excludes
        // centers.updated_at, which moves on writes the partner never made.
        $snapshot['last_activity_at'] = collect([
            $snapshot['profile_updated_at'],
            $snapshot['availability_updated_at'],
            $snapshot['pricing_updated_at'],
            $snapshot['gallery_updated_at'],
            $snapshot['last_retreat_created'],
        ])->filter()->max();

        return $this->cache[$center->id] = $snapshot;
    }

    /** Drop the memo — used by long-running commands between batches. */
    public function flush(): void
    {
        $this->cache = [];
    }

    /**
     * The Center Owner who receives the automation emails.
     *
     * Mirrors the check CenterAuthController@login enforces: the account must actually hold the
     * Owner role, so an email is never sent to a user who could not act on it.
     */
    public function owner(Centers $center): ?User
    {
        if (empty($center->user_id)) {
            return null;
        }

        $user = User::whereNull('deleted_at')->find($center->user_id);

        if (!$user || empty($user->email) || !$user->hasRole('Owner')) {
            return null;
        }

        return $user;
    }

    /**
     * @return array{0: int, 1: array<int, string>}
     */
    public function profileCompleteness(Centers $center): array
    {
        $filled  = 0;
        $missing = [];

        foreach (self::PROFILE_FIELDS as $key => $label) {
            if (!empty($center->{$key})) {
                $filled++;
            } else {
                $missing[] = $label;
            }
        }

        $totalFields = count(self::PROFILE_FIELDS) + 1; // +1 for the gallery check below

        if (CenterImageGallery::where('center_id', $center->id)->exists()) {
            $filled++;
        } else {
            $missing[] = 'Gallery images';
        }

        return [(int) round(($filled / $totalFields) * 100), $missing];
    }

    private function accommodationCount(int $centerId): int
    {
        return (int) DB::table('center_accomodations')
            ->join('accomodation', 'accomodation.id', '=', 'center_accomodations.accomodation_id')
            ->where('center_accomodations.center_id', $centerId)
            ->count();
    }

    /**
     * An accommodation set counts as complete once at least one room is described well enough for
     * a traveler to picture it: a description, a capacity and a banner image.
     */
    private function accommodationComplete(int $centerId): bool
    {
        return DB::table('center_accomodations')
            ->join('accomodation', 'accomodation.id', '=', 'center_accomodations.accomodation_id')
            ->where('center_accomodations.center_id', $centerId)
            ->whereNotNull('accomodation.description')
            ->where('accomodation.description', '!=', '')
            ->whereNotNull('accomodation.banner_image_url')
            ->where('accomodation.banner_image_url', '!=', '')
            ->whereNotNull('accomodation.max_guest_in_room')
            ->exists();
    }

    /**
     * Last time a calendar slot was touched. Falls back to the availability rows themselves for
     * centers that predate the centers.availability_updated_at column.
     */
    private function availabilityUpdatedAt(Centers $center, array $experienceIds): ?Carbon
    {
        if ($center->availability_updated_at) {
            return Carbon::parse($center->availability_updated_at);
        }

        if (empty($experienceIds)) {
            return null;
        }

        $latest = ExperienceAccommodationAvailability::whereIn('experience_id', $experienceIds)
            ->max(DB::raw('GREATEST(COALESCE(updated_at, created_at), COALESCE(created_at, updated_at))'));

        return $latest ? Carbon::parse($latest) : null;
    }

    /**
     * Last time any price was written. Seasonal overrides and duration prices both count.
     */
    private function pricingUpdatedAt(Centers $center, array $experienceIds): ?Carbon
    {
        if ($center->pricing_updated_at) {
            return Carbon::parse($center->pricing_updated_at);
        }

        if (empty($experienceIds)) {
            return null;
        }

        // updated_at is nullable on all three price tables (rows written before Eloquent
        // timestamps were consistent), so take whichever of the two stamps is later.
        $latest = collect([
            'experience_accomodation_prices',
            'experience_accommodation_duration_prices',
            'experience_duration_prices',
        ])->map(function ($table) use ($experienceIds) {
            return DB::table($table)
                ->whereIn('experience_id', $experienceIds)
                ->max(DB::raw('GREATEST(COALESCE(updated_at, created_at), COALESCE(created_at, updated_at))'));
        })->filter()->max();

        return $latest ? Carbon::parse($latest) : null;
    }

    /**
     * Last image upload across the center gallery and every retreat gallery.
     */
    private function galleryUpdatedAt(Centers $center, array $experienceIds): ?Carbon
    {
        if ($center->gallery_updated_at) {
            return Carbon::parse($center->gallery_updated_at);
        }

        $dates = [CenterImageGallery::where('center_id', $center->id)->max('created_at')];

        if (!empty($experienceIds)) {
            $dates[] = ExperienceImageGallery::whereIn('experience_id', $experienceIds)->max('created_at');
        }

        $latest = collect($dates)->filter()->max();

        return $latest ? Carbon::parse($latest) : null;
    }

    /**
     * Bookable date slots in the near future, across all of this center's retreats.
     *
     * A slot only counts when it is genuinely sellable — status 'open' or 'few_left' and with
     * rooms actually left — so a calendar full of 'full' dates correctly reads as low availability.
     */
    private function openSlotsAhead(array $experienceIds): int
    {
        if (empty($experienceIds)) {
            return 0;
        }

        $horizon = (int) config('center_emails.thresholds.low_availability_horizon_days', 90);

        return (int) ExperienceAccommodationAvailability::whereIn('experience_id', $experienceIds)
            ->whereIn('status', ['open', 'few_left'])
            ->whereBetween('start_date', [now()->toDateString(), now()->addDays($horizon)->toDateString()])
            ->whereColumn('booked_rooms', '<', 'total_rooms')
            ->count();
    }

    /**
     * Required fields a retreat is missing, using the same content the public detail page renders.
     * Drives the "Retreat Needs More Information" email.
     *
     * @return array<int, string>
     */
    public function retreatMissingFields(Experiences $experience): array
    {
        $fields = [
            'experience_summary' => 'Retreat description',
            'experience_details' => 'Program details',
            'schedule'           => 'Itinerary',
            'what_is_included'   => 'Includes / excludes',
            'banner_image_url'   => 'Banner image',
        ];

        $missing = [];

        foreach ($fields as $key => $label) {
            if (empty($experience->{$key})) {
                $missing[] = $label;
            }
        }

        if (empty($experience->avg_price) && empty($experience->price_per_person)) {
            $missing[] = 'Pricing';
        }

        if (empty($experience->start_date_time) && empty($experience->is_recurring)) {
            $missing[] = 'Dates or recurring schedule';
        }

        if (!ExperienceImageGallery::where('experience_id', $experience->id)->exists()) {
            $missing[] = 'Gallery images';
        }

        return $missing;
    }
}
