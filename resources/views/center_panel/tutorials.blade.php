@extends('layouts.center')

@section('title', 'Tutorials & Help - BalanceBoat')

@section('content')

    <div class="space-y-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-purple-500">Center Panel Guide</p>
        <h1 class="text-2xl md:text-3xl font-semibold text-slate-900">Tutorials & Help</h1>
        <p class="text-sm text-slate-500 max-w-2xl">Everything you need to know to manage your listings, availability, bookings and profile from the Center Panel.</p>
    </div>

    <div x-data="{ open: 'dashboard' }" class="grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-6 items-start">

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
        <div class="bg-white/90 border border-slate-200 rounded-3xl p-6 md:p-8 space-y-6 min-h-[420px]">

            <div x-show="open === 'dashboard'" x-cloak class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-chart-line text-purple-600"></i><span>Dashboard</span></h2>
                <p class="text-sm text-slate-600">Your Dashboard is the home screen of the Center Panel — a quick snapshot of how your listings are performing.</p>
                <ul class="list-disc list-inside text-sm text-slate-600 space-y-2">
                    <li>View total and active experiences, total bookings, and total inquiries at a glance.</li>
                    <li>Track month-over-month change in bookings and inquiries, shown as a percentage delta versus last month.</li>
                    <li>See your total converted booking value (all-time, normalized to USD).</li>
                    <li>Use the quick links on this screen to jump straight into Retreat Management, Availability, or Bookings.</li>
                </ul>
            </div>

            <div x-show="open === 'profile'" x-cloak class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-user-gear text-purple-600"></i><span>Center Profile</span></h2>
                <p class="text-sm text-slate-600">Keep your center's public profile accurate — this is what guests see before they book with you.</p>
                <ol class="list-decimal list-inside text-sm text-slate-600 space-y-2">
                    <li>Go to <strong>Center Profile</strong> in the sidebar.</li>
                    <li>Update your name, city/region, description, amenities, and center type.</li>
                    <li>Upload a banner image and add photos to your image gallery to showcase your space.</li>
                    <li>Change your login password from the same page if needed.</li>
                    <li>Click <strong>Save</strong> to publish your changes.</li>
                </ol>
            </div>

            <div x-show="open === 'experiences'" x-cloak class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-spa text-purple-600"></i><span>Retreat Management</span></h2>
                <p class="text-sm text-slate-600">Create and manage the retreats/experiences you offer.</p>
                <ol class="list-decimal list-inside text-sm text-slate-600 space-y-2">
                    <li>Open <strong>Retreat Management</strong> and click <strong>Add Experience</strong> to create a new listing.</li>
                    <li>Fill in the name, description, categories, thumbnail image, and pricing details.</li>
                    <li>Use the AI structure-content tool while editing an experience to help format your description content automatically.</li>
                    <li>Mark an experience as a draft while you're still preparing it, and mark it bookable once availability is set up.</li>
                    <li>Edit or remove existing experiences at any time from the experiences list.</li>
                </ol>
            </div>

            <div x-show="open === 'accommodations'" x-cloak class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-bed text-purple-600"></i><span>Accommodations</span></h2>
                <p class="text-sm text-slate-600">Manage the rooms and stay options tied to your center.</p>
                <ol class="list-decimal list-inside text-sm text-slate-600 space-y-2">
                    <li>Go to <strong>Accommodations</strong> and click <strong>Add Accommodation</strong>.</li>
                    <li>Add a banner image and gallery photos for each accommodation type.</li>
                    <li>Edit details any time, or delete an accommodation and its images if it's no longer offered.</li>
                </ol>
            </div>

            <div x-show="open === 'availability'" x-cloak class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-calendar-days text-purple-600"></i><span>Availability & Pricing</span></h2>
                <p class="text-sm text-slate-600">Control when each experience runs and what it costs.</p>
                <ol class="list-decimal list-inside text-sm text-slate-600 space-y-2">
                    <li>Open <strong>Availability & Pricing</strong> and select an experience to manage.</li>
                    <li>Set duration-based prices — remove a price tier with the delete option if it's no longer needed.</li>
                    <li>Use the <strong>Schedule</strong> view to add or update start dates for that experience.</li>
                    <li>Delete outdated start dates to keep your calendar accurate — guests can only book dates you've published.</li>
                </ol>
            </div>

            <div x-show="open === 'commission'" x-cloak class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-percent text-purple-600"></i><span>Commission Engine</span></h2>
                <p class="text-sm text-slate-600">Review and manage the commission terms attached to each experience.</p>
                <ol class="list-decimal list-inside text-sm text-slate-600 space-y-2">
                    <li>Open <strong>Commission Engine</strong> to see commission status across all your experiences.</li>
                    <li>Click <strong>Manage</strong> on an experience to view or update its commission settings, then save your changes.</li>
                </ol>
            </div>

            <div x-show="open === 'leads'" x-cloak class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-inbox text-purple-600"></i><span>Lead Pipeline</span></h2>
                <p class="text-sm text-slate-600">Track and respond to guest inquiries before they turn into bookings.</p>
                <ol class="list-decimal list-inside text-sm text-slate-600 space-y-2">
                    <li>Open <strong>Lead Pipeline</strong> to see every inquiry that's come in.</li>
                    <li>Move a lead through stages as you follow up with the guest.</li>
                    <li>Reply directly to a lead using the respond action on that entry.</li>
                </ol>
            </div>

            <div x-show="open === 'bookings'" x-cloak class="space-y-4">
                <h2 class="text-lg font-semibold text-slate-900 flex items-center space-x-2"><i class="fa-solid fa-calendar-check text-purple-600"></i><span>Bookings Ledger</span></h2>
                <p class="text-sm text-slate-600">The full record of confirmed bookings for your experiences.</p>
                <ol class="list-decimal list-inside text-sm text-slate-600 space-y-2">
                    <li>Open <strong>Bookings Ledger</strong> to see every booking, its guest, dates, and payment amount.</li>
                    <li>Add a booking manually if it was arranged outside the platform.</li>
                    <li>Update a booking's stage as it moves from confirmed through to completed.</li>
                </ol>
            </div>

            <div class="pt-4 mt-2 border-t border-slate-100 text-xs text-slate-400">
                Still need help? Contact the BalanceBoat team and we'll walk you through it.
            </div>
        </div>
    </div>

@endsection
