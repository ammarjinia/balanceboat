@extends('layouts.center')

@section('title', 'Tutorials & Help - BalanceBoat')

@section('head')
<style>
    .tut-shot { cursor: zoom-in; }
    .tut-field-table td, .tut-field-table th { padding: 8px 10px; }
    .tut-field-table tr:nth-child(odd) { background: #fafaf9; }
</style>
@endsection

@section('content')

<div x-data="{ open: 'dashboard', zoom: null }">

    <div class="space-y-1 mb-6">
        <p class="text-[10px] font-bold uppercase tracking-widest text-purple-500">Center Panel Guide</p>
        <h1 class="text-2xl md:text-3xl font-semibold text-slate-900">Tutorials & Help</h1>
        <p class="text-sm text-slate-500 max-w-2xl">A field-by-field walkthrough of every screen in the Center Panel, with real screenshots. Click any screenshot to zoom in.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-6 items-start">

        {{-- Section index --}}
        <nav class="bg-white/90 border border-slate-200 rounded-3xl p-3 space-y-1 lg:sticky lg:top-6">
            @php
                $tutorialSections = [
                    'dashboard'      => ['icon' => 'fa-chart-line',      'label' => 'Dashboard'],
                    'profile'        => ['icon' => 'fa-user-gear',       'label' => 'Center Profile'],
                    'experiences'    => ['icon' => 'fa-spa',             'label' => 'Retreat Management'],
                    'accommodations' => ['icon' => 'fa-bed',             'label' => 'Accommodations'],
                    'availability'   => ['icon' => 'fa-calendar-days',   'label' => 'Availability & Pricing'],
                    'commission'     => ['icon' => 'fa-percent',         'label' => 'Commission Engine'],
                    'leads'          => ['icon' => 'fa-inbox',           'label' => 'Lead Pipeline'],
                    'bookings'       => ['icon' => 'fa-calendar-check',  'label' => 'Bookings Ledger'],
                ];
            @endphp
            @foreach ($tutorialSections as $key => $section)
                <button type="button" @click="open = '{{ $key }}'"
                    class="w-full flex items-center space-x-3 px-3 py-2 rounded-2xl text-xs text-left transition-all"
                    :class="open === '{{ $key }}' ? 'bg-gradient-to-r from-purple-50 to-orange-50 text-purple-700 font-semibold border border-purple-100/60 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'">
                    <i class="fa-solid {{ $section['icon'] }} text-sm w-4"></i>
                    <span>{{ $section['label'] }}</span>
                </button>
            @endforeach
        </nav>

        {{-- Section content --}}
        <div class="space-y-8">

            {{-- ============ DASHBOARD ============ --}}
            <div x-show="open === 'dashboard'" x-cloak class="bg-white/90 border border-slate-200 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-chart-line text-purple-600"></i><span>Dashboard</span></h2>
                <p class="text-sm text-slate-600">Your home screen — a live snapshot of how your listings are performing.</p>

                <img @click="zoom = '{{ asset('images/tutorials/center-panel/dashboard.png') }}'" src="{{ asset('images/tutorials/center-panel/dashboard.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm" alt="Dashboard screenshot">

                <div class="overflow-x-auto">
                    <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wide"><tr><th>Element</th><th>What it means</th></tr></thead>
                        <tbody class="text-slate-600">
                            <tr><td class="font-semibold text-slate-800">Total / Active Experiences</td><td>Total retreats you've created vs. how many are live (not in draft).</td></tr>
                            <tr><td class="font-semibold text-slate-800">Total Bookings / Inquiries</td><td>All-time counts across every experience at this center.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Month-over-month deltas</td><td>The small % change badges compare this calendar month's bookings/inquiries to last month's.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Total Converted Value</td><td>Sum of all-time booking revenue, normalized into a single USD figure regardless of the currency each booking was placed in.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Profile Completeness</td><td>Scores how filled-out your Center Profile is (description, images, amenities, etc.) — a higher score improves how your center is presented to guests.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Retreat Performance Leaderboard</td><td>Ranks your own experiences against each other by bookings/inquiries so you can see which listings are working.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ============ PROFILE ============ --}}
            <div x-show="open === 'profile'" x-cloak class="bg-white/90 border border-slate-200 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-user-gear text-purple-600"></i><span>Center Profile</span></h2>
                <p class="text-sm text-slate-600">Everything here feeds your center's public page — keep it accurate, it's what convinces a guest to book.</p>

                <img @click="zoom = '{{ asset('images/tutorials/center-panel/profile.png') }}'" src="{{ asset('images/tutorials/center-panel/profile.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm" alt="Center Profile screenshot">

                <div class="overflow-x-auto">
                    <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wide"><tr><th>Field</th><th>What to enter</th></tr></thead>
                        <tbody class="text-slate-600">
                            <tr><td class="font-semibold text-slate-800">Center Name *</td><td>Your public business name, e.g. "Amanpuri Wellness Sanctuary". Required.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Email Address / Contact Number / WhatsApp Number</td><td>How guests and BalanceBoat reach you.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Physical Address / City / Country</td><td>Used for search, maps, and the location shown on your listing.</td></tr>
                            <tr><td class="font-semibold text-slate-800">GPS Coordinates</td><td>Optional lat/long pin for the map on your center page.</td></tr>
                            <tr><td class="font-semibold text-slate-800">About the Center</td><td>Your intro paragraph — history, environment, healing approach.</td></tr>
                            <tr><td class="font-semibold text-slate-800">What Sets Us Apart</td><td>Your differentiator — why a guest should pick you over another center.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Our Philosophy / Our Mission</td><td>Core beliefs and what you exist to do — builds trust with guests.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Center Highlights</td><td>Short bullet points added one at a time (e.g. "Ocean-view yoga shala").</td></tr>
                            <tr><td class="font-semibold text-slate-800">Amenities</td><td>Checklist of on-site facilities (pool, spa, restaurant, etc.).</td></tr>
                            <tr><td class="font-semibold text-slate-800">Center Features</td><td>Comma-separated free text for anything not covered by the amenities checklist.</td></tr>
                            <tr><td class="font-semibold text-slate-800">On-site Accommodation</td><td>Toggle whether this center has rooms to offer — turning this on is what unlocks the Accommodations section.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Banner / Hero Image</td><td>The large header image shown at the top of your public page.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Photo Gallery</td><td>Additional photos guests browse through on your listing.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Promo / Cinematic Video URL</td><td>Optional YouTube/Vimeo link embedded on your page.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Founders / Year of Foundation / Awards</td><td>Credibility details shown in your "About" section.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Tags</td><td>Comma-separated keywords used for search matching (e.g. "ayurveda, panchakarma, yoga").</td></tr>
                            <tr><td class="font-semibold text-slate-800">How to Get There / Things to Do Around the Center</td><td>Practical travel info guests look for before booking.</td></tr>
                            <tr><td class="font-semibold text-slate-800">SEO Page Title / Keywords / Meta Description</td><td>Controls how your center appears in search engine results — not shown to guests directly.</td></tr>
                            <tr><td class="font-semibold text-slate-800">Current / New / Confirm Password</td><td>Change your login password from the bottom of this page.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ============ EXPERIENCES ============ --}}
            <div x-show="open === 'experiences'" x-cloak class="bg-white/90 border border-slate-200 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-spa text-purple-600"></i><span>Retreat Management</span></h2>
                <p class="text-sm text-slate-600">Create and manage every retreat you offer. Each retreat is built through a 6-step wizard.</p>

                <img @click="zoom = '{{ asset('images/tutorials/center-panel/experiences-list.png') }}'" src="{{ asset('images/tutorials/center-panel/experiences-list.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm" alt="Experiences list screenshot">
                <p class="text-xs text-slate-500">The list view — each card shows a thumbnail, status (Draft/Live/Bookable), price, and an AI insights panel. Click <strong>Add Experience</strong> to start the wizard, or a card to edit it.</p>

                <div x-data="{ step: 1 }" class="space-y-4 pt-2 border-t border-slate-100">
                    <div class="flex flex-wrap gap-2">
                        @php
                            $expSteps = [
                                1 => 'Identity', 2 => 'Type & Destination', 3 => 'Duration',
                                4 => 'Schedule & Content', 5 => 'Media', 6 => 'Policies & Publish',
                            ];
                        @endphp
                        @foreach ($expSteps as $n => $label)
                            <button type="button" @click="step = {{ $n }}" class="px-3 py-1.5 rounded-full text-[11px] font-semibold transition-all" :class="step === {{ $n }} ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">{{ $n }}. {{ $label }}</button>
                        @endforeach
                    </div>

                    <div x-show="step === 1" x-cloak class="space-y-4">
                        <img @click="zoom = '{{ asset('images/tutorials/center-panel/experience-step1-identity.png') }}'" src="{{ asset('images/tutorials/center-panel/experience-step1-identity.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                        <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                            <tbody class="text-slate-600">
                                <tr><td class="font-semibold text-slate-800 w-1/3">Retreat Title *</td><td>The public name of the retreat, e.g. "7-Day Ayurvedic Panchakarma Retreat in Kerala".</td></tr>
                                <tr><td class="font-semibold text-slate-800">URL Slug</td><td>The web address for this retreat — auto-generated from the title, editable.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Max Guest Capacity</td><td>The most guests that can join a single run of this retreat.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Short Summary</td><td>A 2–3 sentence teaser shown in search results.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Full Description / Overview</td><td>The main body copy — philosophy, environment, the transformation guests can expect.</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div x-show="step === 2" x-cloak class="space-y-4">
                        <img @click="zoom = '{{ asset('images/tutorials/center-panel/experience-step2-type-destination.png') }}'" src="{{ asset('images/tutorials/center-panel/experience-step2-type-destination.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                        <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                            <tbody class="text-slate-600">
                                <tr><td class="font-semibold text-slate-800 w-1/3">Languages Spoken</td><td>Chip-select the languages your instructors/hosts teach in.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Skill / Experience Level</td><td>Beginner, intermediate, advanced, or all levels.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Atmosphere / Setting Tags</td><td>Free-text descriptors like "Jungle, Beachfront, Mountain, Urban".</td></tr>
                                <tr><td class="font-semibold text-slate-800">GPS Coordinates</td><td>Optional — pins this specific retreat's location on the map.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Tags (SEO)</td><td>Comma-separated search keywords, e.g. "ayurveda, detox, yoga, kerala, wellness".</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div x-show="step === 3" x-cloak class="space-y-4">
                        <img @click="zoom = '{{ asset('images/tutorials/center-panel/experience-step3-duration.png') }}'" src="{{ asset('images/tutorials/center-panel/experience-step3-duration.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                        <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                            <tbody class="text-slate-600">
                                <tr><td class="font-semibold text-slate-800 w-1/3">Duration Packages</td><td>Add one or more length options for this retreat (e.g. 3, 7, 21 days) — each can carry its own base price, set later in Availability & Pricing.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Food Type / Meal Options</td><td>E.g. "Vegetarian, Vegan, Sattvic, Organic, All-Inclusive".</td></tr>
                                <tr><td class="font-semibold text-slate-800">Area / Location Details</td><td>A human-readable location line, e.g. "Near Kovalam Beach, Thiruvananthapuram, Kerala".</td></tr>
                                <tr><td class="font-semibold text-slate-800">Meta Title / Meta Description</td><td>SEO fields — leave blank to auto-fill from the title/summary.</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div x-show="step === 4" x-cloak class="space-y-4">
                        <img @click="zoom = '{{ asset('images/tutorials/center-panel/experience-step4-schedule-content.png') }}'" src="{{ asset('images/tutorials/center-panel/experience-step4-schedule-content.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                        <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                            <tbody class="text-slate-600">
                                <tr><td class="font-semibold text-slate-800 w-1/3">Daily Schedule / Timetable</td><td>Paste a plain-text itinerary (e.g. "Day 1 / 7:00am Morning Yoga / 8:00 Breakfast") and it's parsed into a structured schedule — or add rows manually.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Experience Highlights</td><td>Short bullet points added one at a time, shown as quick-scan highlights on the listing.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Full Program Details</td><td>Detailed day-by-day breakdown, therapy descriptions, guest expectations.</td></tr>
                                <tr><td class="font-semibold text-slate-800">✅ What's Included / ❌ What's Not Included</td><td>Two separate lists — sets clear expectations and reduces support questions.</td></tr>
                                <tr><td class="font-semibold text-slate-800">How to Get Here</td><td>Nearest airport, transport options, distances.</td></tr>
                            </tbody>
                        </table>
                        <p class="text-xs text-slate-500 bg-purple-50 border border-purple-100 rounded-xl px-3 py-2"><i class="fa-solid fa-wand-magic-sparkles text-purple-500 mr-1"></i> Tip: use the <strong>Structure with AI</strong> action while editing to auto-format pasted content into these fields.</p>
                    </div>

                    <div x-show="step === 5" x-cloak class="space-y-4">
                        <img @click="zoom = '{{ asset('images/tutorials/center-panel/experience-step5-media.png') }}'" src="{{ asset('images/tutorials/center-panel/experience-step5-media.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                        <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                            <tbody class="text-slate-600">
                                <tr><td class="font-semibold text-slate-800 w-1/3">Thumbnail Image</td><td>Shown in search listings and cards — pick your strongest single photo.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Banner / Hero Image</td><td>The large header image on the retreat's detail page.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Video URL</td><td>Optional YouTube or Vimeo link.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Image Gallery</td><td>Additional photos guests scroll through on the detail page.</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div x-show="step === 6" x-cloak class="space-y-4">
                        <img @click="zoom = '{{ asset('images/tutorials/center-panel/experience-step6-policies-publish.png') }}'" src="{{ asset('images/tutorials/center-panel/experience-step6-policies-publish.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                        <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                            <tbody class="text-slate-600">
                                <tr><td class="font-semibold text-slate-800 w-1/3">Deposit Policy</td><td>Toggle whether a deposit is required, then set the amount or percentage.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Cancellation Policy</td><td>Toggle on, then set the days-before-arrival cutoff and describe your full refund terms in plain language.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Booking Information / Instructions</td><td>Payment methods accepted, what to bring, arrival process.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Draft / Bookable toggles</td><td>Draft keeps the retreat hidden while you finish it; Bookable controls whether guests can actually book once it's live.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ============ ACCOMMODATIONS ============ --}}
            <div x-show="open === 'accommodations'" x-cloak class="bg-white/90 border border-slate-200 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-bed text-purple-600"></i><span>Accommodations</span></h2>
                <p class="text-sm text-slate-600">Manage the room/stay types tied to your center. This section is only usable once "On-site Accommodation" is turned on in your Center Profile.</p>

                <img @click="zoom = '{{ asset('images/tutorials/center-panel/accommodations-list.png') }}'" src="{{ asset('images/tutorials/center-panel/accommodations-list.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">

                <div x-data="{ tab: 'details' }" class="space-y-4 pt-2 border-t border-slate-100">
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="tab = 'details'" class="px-3 py-1.5 rounded-full text-[11px] font-semibold" :class="tab === 'details' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">1. Details</button>
                        <button type="button" @click="tab = 'media'" class="px-3 py-1.5 rounded-full text-[11px] font-semibold" :class="tab === 'media' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">2. Media & Assets</button>
                        <button type="button" @click="tab = 'review'" class="px-3 py-1.5 rounded-full text-[11px] font-semibold" :class="tab === 'review' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">3. Review & Publish</button>
                    </div>

                    <div x-show="tab === 'details'" x-cloak class="space-y-4">
                        <img @click="zoom = '{{ asset('images/tutorials/center-panel/accommodation-edit-details.png') }}'" src="{{ asset('images/tutorials/center-panel/accommodation-edit-details.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                        <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                            <tbody class="text-slate-600">
                                <tr><td class="font-semibold text-slate-800 w-1/3">Name</td><td>The room/suite type name, e.g. "Deluxe Private Garden Suite".</td></tr>
                                <tr><td class="font-semibold text-slate-800">Slug</td><td>Auto-generated URL identifier from the name.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Max Allocation Guest Capacity</td><td>How many guests this room type sleeps.</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div x-show="tab === 'media'" x-cloak class="space-y-4">
                        <img @click="zoom = '{{ asset('images/tutorials/center-panel/accommodation-edit-media.png') }}'" src="{{ asset('images/tutorials/center-panel/accommodation-edit-media.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                        <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                            <tbody class="text-slate-600">
                                <tr><td class="font-semibold text-slate-800 w-1/3">Primary Cover Backdrop Image</td><td>The main photo shown for this accommodation.</td></tr>
                                <tr><td class="font-semibold text-slate-800">Supplementary Asset Gallery Grid</td><td>Additional room photos guests can browse.</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div x-show="tab === 'review'" x-cloak class="space-y-4">
                        <img @click="zoom = '{{ asset('images/tutorials/center-panel/accommodation-edit-review.png') }}'" src="{{ asset('images/tutorials/center-panel/accommodation-edit-review.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                        <p class="text-xs text-slate-500">A final preview of everything you entered before it's saved and shown to guests. Go back to any earlier tab to fix something, or confirm to publish.</p>
                    </div>
                </div>
            </div>

            {{-- ============ AVAILABILITY ============ --}}
            <div x-show="open === 'availability'" x-cloak class="bg-white/90 border border-slate-200 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-calendar-days text-purple-600"></i><span>Availability & Pricing</span></h2>
                <p class="text-sm text-slate-600">Controls when each experience runs and what it costs. There are two layers: base duration pricing, and optional seasonal/promo overrides — the most specific rule that matches a date wins.</p>

                <img @click="zoom = '{{ asset('images/tutorials/center-panel/availability-list.png') }}'" src="{{ asset('images/tutorials/center-panel/availability-list.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-xs text-slate-500">Pick an experience from this list, then click <strong>Manage</strong> to set its pricing.</p>

                <img @click="zoom = '{{ asset('images/tutorials/center-panel/availability-manage.png') }}'" src="{{ asset('images/tutorials/center-panel/availability-manage.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                    <tbody class="text-slate-600">
                        <tr><td class="font-semibold text-slate-800 w-1/3">Base duration price</td><td>The default per-guest price for each duration package you defined in the retreat wizard (e.g. one price for the 7-day option, another for the 21-day option).</td></tr>
                        <tr><td class="font-semibold text-slate-800">Seasonal / promo price override</td><td>Two price fields plus an optional label (e.g. "10% off, Early Bird") — use this to override the base price for a specific date range without editing the base price itself.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Delete price</td><td>Removes a duration or override row entirely — do this rather than zeroing it out if it no longer applies.</td></tr>
                    </tbody>
                </table>

                <img @click="zoom = '{{ asset('images/tutorials/center-panel/availability-schedule.png') }}'" src="{{ asset('images/tutorials/center-panel/availability-schedule.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                    <tbody class="text-slate-600">
                        <tr><td class="font-semibold text-slate-800 w-1/3">Start Date calendar</td><td>Click a date to add it as a run of this retreat — guests can only book dates you've published here.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Total / Booked</td><td>Total spots available for that start date, and how many are already booked.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Delete start date</td><td>Removes an upcoming run — do this if a date is cancelled.</td></tr>
                    </tbody>
                </table>
                <p class="text-xs text-slate-500 bg-amber-50 border border-amber-100 rounded-xl px-3 py-2"><i class="fa-solid fa-circle-info text-amber-500 mr-1"></i> The calendar only shows dots on months that actually have a start date — it opens on the current month, so if you don't see anything, page forward/back to the month you scheduled. All scheduled dates are also listed in the accommodation overview table below the calendar.</p>
            </div>

            {{-- ============ COMMISSION ============ --}}
            <div x-show="open === 'commission'" x-cloak class="bg-white/90 border border-slate-200 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-percent text-purple-600"></i><span>Commission Engine</span></h2>
                <p class="text-sm text-slate-600">Review and set the commission percentage BalanceBoat takes on bookings for each experience.</p>

                <img @click="zoom = '{{ asset('images/tutorials/center-panel/commission-list.png') }}'" src="{{ asset('images/tutorials/center-panel/commission-list.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-xs text-slate-500">Shows commission status across every experience. Click <strong>Manage</strong> on one to open its detail view.</p>

                <img @click="zoom = '{{ asset('images/tutorials/center-panel/commission-manage.png') }}'" src="{{ asset('images/tutorials/center-panel/commission-manage.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">
                <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                    <tbody class="text-slate-600">
                        <tr><td class="font-semibold text-slate-800 w-1/3">Commission slider</td><td>Drag to set the commission rate for this experience — it snaps to the nearest of several preset percentages rather than an arbitrary number.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Save</td><td>Applies the new rate to future bookings on this experience.</td></tr>
                    </tbody>
                </table>
            </div>

            {{-- ============ LEADS ============ --}}
            <div x-show="open === 'leads'" x-cloak class="bg-white/90 border border-slate-200 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-inbox text-purple-600"></i><span>Lead Pipeline</span></h2>
                <p class="text-sm text-slate-600">Track and respond to guest inquiries before they turn into bookings.</p>

                <img @click="zoom = '{{ asset('images/tutorials/center-panel/leads.png') }}'" src="{{ asset('images/tutorials/center-panel/leads.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">

                <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wide"><tr><th>Field / element</th><th>What it means</th></tr></thead>
                    <tbody class="text-slate-600">
                        <tr><td class="font-semibold text-slate-800">Pipeline stages</td><td>Each lead moves through stages (e.g. New → Proposal Sent → Won/Lost) — drag or update the stage as you follow up.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Weighted Pipeline</td><td>An estimate of pipeline value that discounts each lead's deal value by how likely it is to close based on its current stage.</td></tr>
                        <tr><td class="font-semibold text-slate-800">SLA alert</td><td>Flags a lead that hasn't been responded to within the expected response window, so it doesn't go cold.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Prospect Name / Email / Phone</td><td>Contact details when manually adding a lead that came in outside the platform.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Target Retreat Program</td><td>Which of your experiences this prospect is interested in.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Value Allocation</td><td>Your estimate of what this lead is worth if it converts — feeds the Weighted Pipeline figure.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Notes / Context</td><td>Free text, e.g. "Referred by past guest, interested in group booking".</td></tr>
                        <tr><td class="font-semibold text-slate-800">Direct Center Response Console</td><td>Reply to the prospect directly from the lead row.</td></tr>
                    </tbody>
                </table>
            </div>

            {{-- ============ BOOKINGS ============ --}}
            <div x-show="open === 'bookings'" x-cloak class="bg-white/90 border border-slate-200 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-calendar-check text-purple-600"></i><span>Bookings Ledger</span></h2>
                <p class="text-sm text-slate-600">The full record of confirmed bookings for your experiences.</p>

                <img @click="zoom = '{{ asset('images/tutorials/center-panel/bookings.png') }}'" src="{{ asset('images/tutorials/center-panel/bookings.png') }}" class="tut-shot w-full rounded-2xl border border-slate-200 shadow-sm">

                <table class="tut-field-table w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wide"><tr><th>Field</th><th>What it means</th></tr></thead>
                    <tbody class="text-slate-600">
                        <tr><td class="font-semibold text-slate-800">Customer Seeker Name / Target Sanctuary</td><td>The guest's name and which experience they booked.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Room / Suite Category / Pax Capacity Count</td><td>Which accommodation type was booked and for how many guests.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Booking Date Signature</td><td>The date the booking was made.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Arrival Check-In Target</td><td>The guest's actual arrival/check-in date — different from the booking date.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Gross Price</td><td>The full price paid before any discount or commission is deducted.</td></tr>
                        <tr><td class="font-semibold text-slate-800">BB Discount</td><td>Any platform discount applied to this booking.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Commission % Matrix</td><td>The commission rate applied to this specific booking (comes from the Commission Engine setting at the time of booking).</td></tr>
                        <tr><td class="font-semibold text-slate-800">Current Status</td><td>Where this booking is in its lifecycle (e.g. confirmed, checked-in, completed).</td></tr>
                        <tr><td class="font-semibold text-slate-800">Extra Requirements / Amenities Logistics</td><td>Guest-facing notes like airport transfers or custom setup requests.</td></tr>
                        <tr><td class="font-semibold text-slate-800">Internal Sanctuary Host Notes</td><td>Private notes visible only to your team, not the guest.</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="text-xs text-slate-400 px-2">
                Still need help? Contact the BalanceBoat team and we'll walk you through it.
            </div>
        </div>
    </div>

    {{-- Lightbox --}}
    <div x-show="zoom" x-cloak @click="zoom = null" @keydown.escape.window="zoom = null"
         class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 md:p-10 cursor-zoom-out">
        <img :src="zoom" class="max-w-full max-h-full rounded-xl shadow-2xl" @click.stop>
    </div>
</div>

@endsection
