<?php

/*
|--------------------------------------------------------------------------
| Center Panel Email Automation
|--------------------------------------------------------------------------
|
| Every automated email a center partner can receive is declared here. The
| evaluators in app/Console/Commands/CenterEmails* decide *whether* a center
| qualifies; this file decides what gets sent, how it is worded and how often
| it may repeat. Subjects and preview text are taken verbatim from the
| "Email Copy + Design Document".
|
| Per-email keys:
|
|   group        Reporting bucket: onboarding | maintenance | performance | trust | ai
|   enabled      Kill switch. A disabled email is never queued.
|   view         Blade template under resources/views/center_panel/emails/automation/
|   subject      Supports {center_name} and {first_name} placeholders.
|   preview      Inbox preview text, injected into the layout's hidden preheader.
|   cta          ['label' => ..., 'route' => ...] — the single primary CTA button.
|   footer       Which footer line to use: operational | performance | ai
|   once         true = send at most one per center, ever (e.g. welcome).
|   cooldown_days  Minimum days between two sends of this key to the same center.
|   max_sends    Optional cap on total sends per center (nudge sequences stop nagging).
|   transactional  true = always delivered, even to opted-out partners.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Global controls
    |--------------------------------------------------------------------------
    */

    // Master switch. Leave false until the schedule has been verified on staging.
    'enabled' => env('CENTER_EMAILS_ENABLED', false),

    // When set, every automation email is redirected here instead of the partner.
    // Use this to dry-run the full schedule against production data safely.
    'redirect_to' => env('CENTER_EMAILS_REDIRECT_TO'),

    // Hard ceiling on non-transactional automation emails per center per rolling 7 days.
    // Section 7 of the spec stacks weekly, biweekly and monthly cadences on top of each
    // other; without a cap an inactive center with a thin listing qualifies for most of
    // them at once.
    'weekly_cap' => env('CENTER_EMAILS_WEEKLY_CAP', 2),

    // Safety valve for the scheduled commands — how many centers one run may email.
    'batch_limit' => env('CENTER_EMAILS_BATCH_LIMIT', 200),

    // Centers created before this date are treated as pre-existing and are excluded from
    // the onboarding sequence, which would otherwise "welcome" 11,000 legacy listings on
    // the first cron run. Null disables onboarding entirely.
    'onboarding_since' => env('CENTER_EMAILS_ONBOARDING_SINCE', '2026-09-20'),

    'support_email' => env('CENTER_EMAILS_SUPPORT', 'support@balanceboat.com'),

    /*
    |--------------------------------------------------------------------------
    | Trigger thresholds (spec section 4)
    |--------------------------------------------------------------------------
    */

    'thresholds' => [
        'profile_reminder_after_days'   => 1,
        'first_retreat_after_days'      => 2,
        'availability_stale_days'       => 14,
        'pricing_stale_days'            => 30,
        'no_new_retreats_days'          => 30,
        'gallery_stale_days'            => 60,
        'inactive_login_days'           => 30,
        'listing_outdated_days'         => 60,
        'annual_audit_days'             => 365,
        // A retreat with fewer than this many future open date slots is "low availability".
        'low_availability_slots'        => 3,
        // ...counted this far ahead.
        'low_availability_horizon_days' => 90,
    ],

    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    */

    'templates' => [

        /* ── A. Onboarding and setup ─────────────────────────────────────── */

        'welcome' => [
            'group'         => 'onboarding',
            'enabled'       => true,
            'view'          => 'welcome',
            'subject'       => 'Welcome to BalanceBoat, {center_name}',
            'preview'       => 'Your dashboard is ready. Let\'s get your center set up for more visibility and bookings.',
            'cta'           => ['label' => 'Go to Your Dashboard', 'route' => 'center-panel.dashboard'],
            'footer'        => 'operational',
            'once'          => true,
            'transactional' => true,
        ],

        'profile_incomplete' => [
            'group'         => 'onboarding',
            'enabled'       => true,
            'view'          => 'profile_incomplete',
            'subject'       => 'Complete your center profile to start building trust',
            'preview'       => 'Add your photos, description, and facilities so travelers can understand your center better.',
            'cta'           => ['label' => 'Complete Profile', 'route' => 'center-panel.settings'],
            'footer'        => 'operational',
            'cooldown_days' => 3,
            'max_sends'     => 4,
        ],

        'first_retreat' => [
            'group'         => 'onboarding',
            'enabled'       => true,
            'view'          => 'first_retreat',
            'subject'       => 'Add your first retreat and start attracting inquiries',
            'preview'       => 'Retreat listings help travelers understand what you offer and why they should choose you.',
            'cta'           => ['label' => 'Add Your First Retreat', 'route' => 'center-panel.experience.create'],
            'footer'        => 'operational',
            'cooldown_days' => 5,
            'max_sends'     => 4,
        ],

        'accommodation_missing' => [
            'group'         => 'onboarding',
            'enabled'       => true,
            'view'          => 'accommodation_missing',
            'subject'       => 'Add accommodation details to improve bookings',
            'preview'       => 'Room types, occupancy, and amenities help travelers make faster decisions.',
            'cta'           => ['label' => 'Add Accommodation Details', 'route' => 'center-panel.accommodation.create'],
            'footer'        => 'operational',
            'cooldown_days' => 3,
            'max_sends'     => 3,
        ],

        'availability_setup' => [
            'group'         => 'onboarding',
            'enabled'       => true,
            'view'          => 'availability_setup',
            'subject'       => 'Set your availability so travelers can book with confidence',
            'preview'       => 'An updated calendar helps avoid missed opportunities and outdated listings.',
            'cta'           => ['label' => 'Update Availability', 'route' => 'center-panel.availability'],
            'footer'        => 'operational',
            'cooldown_days' => 14,
            'max_sends'     => 4,
        ],

        'pricing_update' => [
            'group'         => 'onboarding',
            'enabled'       => true,
            'view'          => 'pricing_update',
            'subject'       => 'Review your pricing and stay competitive',
            'preview'       => 'Updated pricing helps your listing stay relevant for the current season.',
            'cta'           => ['label' => 'Update Pricing', 'route' => 'center-panel.availability'],
            'footer'        => 'operational',
            'cooldown_days' => 30,
        ],

        /* ── B. Ongoing listing health ───────────────────────────────────── */

        'availability_reminder' => [
            'group'         => 'maintenance',
            'enabled'       => true,
            'view'          => 'availability_reminder',
            'subject'       => 'Your availability calendar needs a refresh',
            'preview'       => 'Keep your retreat bookable by updating future dates and open slots.',
            'cta'           => ['label' => 'Review Availability', 'route' => 'center-panel.availability'],
            'footer'        => 'operational',
            'cooldown_days' => 14,
        ],

        'new_retreats' => [
            'group'         => 'maintenance',
            'enabled'       => true,
            'view'          => 'new_retreats',
            'subject'       => 'Add a new retreat to keep your listing fresh',
            'preview'       => 'New experiences help your center stay visible and relevant.',
            'cta'           => ['label' => 'Add New Retreat', 'route' => 'center-panel.experience.create'],
            'footer'        => 'operational',
            'cooldown_days' => 30,
        ],

        'fresh_photos' => [
            'group'         => 'maintenance',
            'enabled'       => true,
            'view'          => 'fresh_photos',
            'subject'       => 'Fresh photos can improve traveler interest',
            'preview'       => 'New visuals help your center feel current, inviting, and trustworthy.',
            'cta'           => ['label' => 'Upload Photos', 'route' => 'center-panel.settings'],
            'footer'        => 'operational',
            'cooldown_days' => 60,
        ],

        'retreat_incomplete' => [
            'group'         => 'maintenance',
            'enabled'       => true,
            'view'          => 'retreat_incomplete',
            'subject'       => 'A few retreat details are still missing',
            'preview'       => 'Complete the missing fields so your retreat can perform better in search.',
            'cta'           => ['label' => 'Complete Retreat Details', 'route' => 'center-panel.experiences'],
            'footer'        => 'operational',
            // Dispatched inline from the retreat form, so this is a per-center anti-spam
            // window rather than a cadence — saving a draft five times in an hour must not
            // send five emails.
            'cooldown_days' => 3,
        ],

        'low_availability' => [
            'group'         => 'maintenance',
            'enabled'       => true,
            'view'          => 'low_availability',
            'subject'       => 'Your retreat has very few open dates left',
            'preview'       => 'Opening more availability can help you capture more inquiries.',
            'cta'           => ['label' => 'Open More Dates', 'route' => 'center-panel.availability'],
            'footer'        => 'operational',
            'cooldown_days' => 7,
        ],

        'inactive_dashboard' => [
            'group'         => 'maintenance',
            'enabled'       => true,
            'view'          => 'inactive_dashboard',
            'subject'       => 'We have not seen you in your dashboard recently',
            'preview'       => 'Log in to review your listing and keep your information current.',
            'cta'           => ['label' => 'Log In Now', 'route' => 'center-panel.login'],
            'footer'        => 'operational',
            'cooldown_days' => 30,
            'max_sends'     => 6,
        ],

        'listing_outdated' => [
            'group'         => 'maintenance',
            'enabled'       => true,
            'view'          => 'listing_outdated',
            'subject'       => 'Your listing could use a refresh',
            'preview'       => 'Small updates can make a big difference in visibility and trust.',
            'cta'           => ['label' => 'Refresh Listing', 'route' => 'center-panel.dashboard'],
            'footer'        => 'operational',
            'cooldown_days' => 60,
        ],

        'annual_health_check' => [
            'group'         => 'maintenance',
            'enabled'       => true,
            'view'          => 'annual_health_check',
            'subject'       => 'It is time for your annual listing review',
            'preview'       => 'Review your center profile from top to bottom and keep it fully updated.',
            'cta'           => ['label' => 'Start Annual Review', 'route' => 'center-panel.dashboard'],
            'footer'        => 'operational',
            'cooldown_days' => 365,
        ],

        /*
        | ── C/D/E. Performance, trust and AI recommendation emails ─────────
        |
        | Emails 15–32 of the copy document are not part of this pass. Each one is added
        | here with its config block and a matching evaluator; the copy and design
        | direction already exist in the document, and the sending path below is
        | template-agnostic, so adding one is: a config entry, a Blade view, and a
        | qualification check in an evaluator.
        |
        | Note the data these still need:
        |   - 16, 20, 21, 28 (destination demand, seasonal windows, regional trends) have
        |     no data source in the platform today. experience_views.country_code is the
        |     only demand signal that exists and it measures *inbound* traffic to a listing,
        |     not marketplace search demand.
        |   - 23, 24 (reviews) need the center_response columns added in this pass to be
        |     writable from a center-panel UI.
        |   - 27, 29, 31, 32 map closely onto YieldOptimizationInsightsService, which
        |     already produces grounded per-center insight cards from real numbers.
        */
    ],
];
