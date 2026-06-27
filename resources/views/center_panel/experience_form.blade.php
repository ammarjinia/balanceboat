@extends('layouts.center')

@section('title', ($experience ? 'Edit' : 'Create') . ' Retreat — BalanceBoat')

@section('content')

{{-- Flash messages --}}
@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-2xl px-5 py-3 mb-4 flex items-center space-x-2">
        <i class="fa-solid fa-circle-check text-emerald-500"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif
@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 text-xs rounded-2xl px-5 py-3 mb-4 space-y-1">
        @foreach($errors->all() as $error)
            <div class="flex items-center space-x-2"><i class="fa-solid fa-circle-exclamation text-red-400"></i><span>{{ $error }}</span></div>
        @endforeach
    </div>
@endif

{{-- Page Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
    <div>
        <div class="flex items-center space-x-2 mb-1">
            <a href="{{ route('center-panel.experiences') }}" class="text-slate-400 hover:text-slate-700 text-xs transition-all flex items-center space-x-1">
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                <span>Retreat Management</span>
            </a>
        </div>
        <h1 class="text-3xl font-serif font-light text-slate-900">{{ $pageTitle }}</h1>
        <p class="text-xs text-slate-500 mt-1">Complete all steps to {{ $experience ? 'update your' : 'publish a new' }} retreat program.</p>
    </div>
    <div class="flex items-center space-x-2 text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2 shrink-0">
        <i class="fa-regular fa-clock text-slate-400"></i>
        <span>Takes about <strong>5 minutes</strong></span>
    </div>
</div>

{{-- Wizard Container --}}
<div x-data="wizardApp()" x-init="init()" class="space-y-6">

    {{-- Client-side validation banner --}}
    <div x-show="Object.keys(errors).length > 0"
         x-transition
         class="bg-red-50 border border-red-200 text-red-700 text-xs rounded-2xl px-5 py-3 flex items-start space-x-3"
         style="display:none">
        <i class="fa-solid fa-triangle-exclamation text-red-400 mt-0.5 shrink-0"></i>
        <div>
            <p class="font-semibold mb-1">Please fix the following before continuing:</p>
            <ul class="space-y-0.5">
                <template x-for="msg in Object.values(errors)" :key="msg">
                    <li x-text="msg" class="before:content-['•'] before:mr-1.5"></li>
                </template>
            </ul>
        </div>
    </div>

    {{-- Step Progress Bar --}}
    <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm">
        <div class="flex items-center justify-between gap-2">
            @php
            $steps = [
                ['icon' => 'fa-solid fa-id-card',      'label' => 'Identity'],
                ['icon' => 'fa-solid fa-tags',          'label' => 'Type & Destination'],
                ['icon' => 'fa-solid fa-coins',         'label' => 'Pricing'],
                ['icon' => 'fa-regular fa-calendar',    'label' => 'Schedule & Content'],
                ['icon' => 'fa-regular fa-image',       'label' => 'Media'],
                ['icon' => 'fa-solid fa-shield-check',  'label' => 'Policies & Publish'],
            ];
            @endphp
            @foreach($steps as $i => $step)
                <div class="flex flex-col items-center gap-1.5 flex-1 cursor-pointer group" @click="goTo({{ $i + 1 }})">
                    <div class="relative w-full flex items-center">
                        @if($i > 0)
                            <div class="flex-1 h-0.5 transition-all duration-300"
                                 :class="currentStep > {{ $i }} ? 'bg-purple-500' : 'bg-slate-200'"></div>
                        @endif
                        <div class="relative shrink-0 h-9 w-9 rounded-full flex items-center justify-center text-[11px] font-bold transition-all duration-300 border-2 z-10"
                             :class="{
                                'bg-purple-600 border-purple-600 text-white shadow-md shadow-purple-200': currentStep === {{ $i + 1 }},
                                'bg-purple-50 border-purple-400 text-purple-600': currentStep > {{ $i + 1 }},
                                'bg-white border-slate-200 text-slate-400': currentStep < {{ $i + 1 }}
                             }">
                            <template x-if="currentStep > {{ $i + 1 }}">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </template>
                            <template x-if="currentStep <= {{ $i + 1 }}">
                                <i class="{{ $step['icon'] }} text-[10px]"></i>
                            </template>
                        </div>
                        @if($i < count($steps) - 1)
                            <div class="flex-1 h-0.5 transition-all duration-300"
                                 :class="currentStep > {{ $i + 1 }} ? 'bg-purple-500' : 'bg-slate-200'"></div>
                        @endif
                    </div>
                    <span class="text-[9px] font-semibold uppercase tracking-wider transition-all duration-200 hidden md:block"
                          :class="currentStep === {{ $i + 1 }} ? 'text-purple-600' : 'text-slate-400'">
                        {{ $step['label'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Form --}}
    <form id="experienceForm" method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
        @csrf

        {{-- ══════════════════════════════════════════════════════
             STEP 1 — Identity & Overview
        ══════════════════════════════════════════════════════ --}}
        <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <div class="h-7 w-7 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-id-card text-purple-600 text-[10px]"></i>
                    </div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Program Identity</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="wiz-label">Retreat Title <span class="text-red-400">*</span></label>
                        <input type="text" name="name" id="field_name"
                               value="{{ old('name', $experience?->name) }}"
                               x-on:input="autoSlug(); clearError('name')"
                               :class="errors.name ? 'border-red-400 bg-red-50 focus:border-red-400' : ''"
                               class="wiz-input" placeholder="e.g., 7-Day Ayurvedic Panchakarma Retreat in Kerala">
                        @error('name')<p class="wiz-err">{{ $message }}</p>@enderror
                        <p class="wiz-err mt-1" x-show="errors.name" x-text="errors.name" style="display:none"></p>
                    </div>

                    <div>
                        <label class="wiz-label">URL Slug</label>
                        <div class="flex items-center space-x-2">
                            <input type="text" name="slug" id="field_slug"
                                   value="{{ old('slug', $experience?->slug) }}"
                                   class="wiz-input font-mono text-[11px]" placeholder="7-day-ayurvedic-panchakarma-retreat">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Auto-generated from title. You can edit it.</p>
                    </div>

                    <div>
                        <label class="wiz-label">Max Guest Capacity</label>
                        <input type="number" name="batch_size" min="1" max="500"
                               value="{{ old('batch_size', $experience?->batch_size ?? 12) }}"
                               class="wiz-input font-mono">
                    </div>
                </div>

                <div>
                    <label class="wiz-label">Short Summary <span class="text-slate-400 font-normal">(shown in search results)</span></label>
                    <textarea name="experience_summary" rows="3" class="wiz-input resize-none"
                              placeholder="A concise 2–3 sentence description of the retreat experience...">{{ old('experience_summary', $experience?->experience_summary) }}</textarea>
                </div>

                <div>
                    <label class="wiz-label">Full Description / Overview</label>
                    <textarea name="experience_overview" rows="5" class="wiz-input resize-y"
                              placeholder="Describe the retreat in detail — the philosophy, the environment, the transformation guests can expect...">{{ old('experience_overview', $experience?->experience_overview) }}</textarea>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-3">Program Parameters</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="wiz-label">Languages Spoken</label>
                        @php $langSpoken = old('language_spoken', $experience ? explode('||', $experience->language_spoken ?? '') : ['English']); @endphp
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach(['English', 'Hindi', 'German', 'Spanish', 'French', 'Italian', 'Russian', 'Mandarin'] as $lang)
                                <label class="wiz-chip-label">
                                    <input type="checkbox" name="language_spoken[]" value="{{ $lang }}"
                                           {{ in_array($lang, (array)$langSpoken) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <span class="wiz-chip">{{ $lang }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="wiz-label">Skill / Experience Level</label>
                        @php $skillLevel = old('skill_level', $experience?->skill_level ?? 'All Levels'); @endphp
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach(['All Levels', 'Beginner', 'Intermediate', 'Advanced'] as $level)
                                <label class="wiz-chip-label">
                                    <input type="radio" name="skill_level" value="{{ $level }}"
                                           {{ $skillLevel === $level ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <span class="wiz-chip">{{ $level }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="wiz-label">Atmosphere / Setting Tags</label>
                        <input type="text" name="atmosphere"
                               value="{{ old('atmosphere', $experience?->atmosphere) }}"
                               class="wiz-input" placeholder="e.g., Jungle, Beachfront, Mountain, Urban">
                        <p class="text-[10px] text-slate-400 mt-1">Comma-separated keywords.</p>
                    </div>

                    <div>
                        <label class="wiz-label">GPS Coordinates <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="text" name="gps"
                               value="{{ old('gps', $experience?->gps) }}"
                               class="wiz-input font-mono" placeholder="e.g., 10.5276, 76.2144">
                    </div>
                </div>

                <div>
                    <label class="wiz-label">Tags <span class="text-slate-400 font-normal">(SEO, comma-separated)</span></label>
                    <input type="text" name="tags" value="{{ old('tags', $experience?->tags) }}"
                           class="wiz-input" placeholder="ayurveda, detox, yoga, kerala, wellness">
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             STEP 2 — Retreat Type & Destination
        ══════════════════════════════════════════════════════ --}}
        <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <div class="h-7 w-7 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-tags text-purple-600 text-[10px]"></i>
                    </div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Retreat Type & Modalities</h3>
                </div>

                @php
                $selectedCats = old('experience_category_id', $experience?->selectedCategories ?? []);
                $selectedCats = array_map('intval', (array)$selectedCats);

                $typeIcons = [
                    'Yoga' => '🧘', 'Ayurveda' => '🌿', 'Detox' => '🥗', 'Meditation' => '🕉️',
                    'Panchakarma' => '🌺', 'Wellness' => '💚', 'Silence' => '🤫', 'Weight' => '⚖️',
                    'Women' => '👩', 'Couple' => '💑', 'Family' => '👨‍👩‍👧', 'Healing' => '✨',
                    'Burnout' => '🔥', 'Breathwork' => '🌬️', 'Sound' => '🎵', 'Digital' => '📵',
                    'Spiritual' => '🙏', 'Luxury' => '👑', 'Adventure' => '🏕️', 'Eco' => '🌳',
                ];
                @endphp

                @if($retreatCategories->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach($retreatCategories as $cat)
                            @php
                            $icon = '🌿';
                            foreach($typeIcons as $kw => $ic) {
                                if(stripos($cat->name, $kw) !== false) { $icon = $ic; break; }
                            }
                            @endphp
                            <label class="relative cursor-pointer">
                                <input type="checkbox" name="experience_category_id[]" value="{{ $cat->id }}"
                                       {{ in_array($cat->id, $selectedCats) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="flex flex-col items-center p-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-center text-xs transition-all duration-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 hover:border-slate-300 peer-checked:shadow-sm">
                                    <span class="text-xl mb-1.5">{{ $icon }}</span>
                                    <span class="font-medium text-slate-700 peer-checked:text-purple-700 leading-tight text-[11px]">{{ $cat->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                <?php /*@else
                    {{-- Fallback hardcoded modalities --}}
                    @php
                    $hardModalities = [
                        ['label' => 'Yoga Retreat',       'icon' => '🧘'],
                        ['label' => 'Ayurveda Retreat',   'icon' => '🌿'],
                        ['label' => 'Detox Program',      'icon' => '🥗'],
                        ['label' => 'Meditation Retreat', 'icon' => '🕉️'],
                        ['label' => 'Panchakarma',        'icon' => '🌺'],
                        ['label' => 'Wellness Retreat',   'icon' => '💚'],
                        ['label' => 'Silent Retreat',     'icon' => '🤫'],
                        ['label' => 'Weight Loss',        'icon' => '⚖️'],
                        ['label' => "Women's Retreat",    'icon' => '👩'],
                        ['label' => 'Couples Retreat',    'icon' => '💑'],
                        ['label' => 'Family Retreat',     'icon' => '👨‍👩‍👧'],
                        ['label' => 'Healing Retreat',    'icon' => '✨'],
                        ['label' => 'Burnout Recovery',   'icon' => '🔥'],
                        ['label' => 'Breathwork',         'icon' => '🌬️'],
                        ['label' => 'Sound Healing',      'icon' => '🎵'],
                        ['label' => 'Digital Detox',      'icon' => '📵'],
                        ['label' => 'Spiritual Retreat',  'icon' => '🙏'],
                        ['label' => 'Luxury Retreat',     'icon' => '👑'],
                        ['label' => 'Adventure Retreat',  'icon' => '🏕️'],
                        ['label' => 'Eco Retreat',        'icon' => '🌳'],
                    ];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach($hardModalities as $mod)
                            <label class="relative cursor-pointer">
                                <input type="checkbox" name="retreat_type[]" value="{{ $mod['label'] }}"
                                       class="sr-only peer">
                                <div class="flex flex-col items-center p-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-center transition-all duration-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 hover:border-slate-300">
                                    <span class="text-xl mb-1.5">{{ $mod['icon'] }}</span>
                                    <span class="font-medium text-slate-700 text-[11px] leading-tight">{{ $mod['label'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>*/?>
                @endif
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <div class="h-7 w-7 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-earth-asia text-emerald-600 text-[10px]"></i>
                    </div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Destination</h3>
                </div>

                @php
                $destIcons = ['India'=>'🇮🇳','Thailand'=>'🇹🇭','Indonesia'=>'🇮🇩','Bali'=>'🇮🇩','Sri Lanka'=>'🇱🇰','Nepal'=>'🇳🇵','Cambodia'=>'🇰🇭','Vietnam'=>'🇻🇳','Myanmar'=>'🇲🇲','Maldives'=>'🇲🇻'];
                @endphp

                @if($destinations->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach($destinations as $dest)
                            @php
                            $destIcon = '🌏';
                            foreach($destIcons as $kw => $ic) {
                                if(stripos($dest->name, $kw) !== false) { $destIcon = $ic; break; }
                            }
                            @endphp
                            <label class="relative cursor-pointer">
                                <input type="checkbox" name="experience_category_id[]" value="{{ $dest->id }}"
                                       {{ in_array($dest->id, $selectedCats) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="flex items-center space-x-2 p-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-xs transition-all duration-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-slate-300">
                                    <span class="text-lg">{{ $destIcon }}</span>
                                    <span class="font-medium text-slate-700 text-[11px]">{{ $dest->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach([['India','🇮🇳'],['Thailand','🇹🇭'],['Indonesia / Bali','🇮🇩'],['Sri Lanka','🇱🇰'],['Nepal','🇳🇵'],['Cambodia','🇰🇭'],['Vietnam','🇻🇳'],['Maldives','🇲🇻']] as [$dname,$dflag])
                            <label class="relative cursor-pointer">
                                <input type="radio" name="destination_fallback" value="{{ $dname }}" class="sr-only peer">
                                <div class="flex items-center space-x-2 p-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-xs transition-all duration-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-slate-300">
                                    <span class="text-lg">{{ $dflag }}</span>
                                    <span class="font-medium text-slate-700">{{ $dname }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             STEP 3 — Pricing & Duration
        ══════════════════════════════════════════════════════ --}}
        <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <div class="h-7 w-7 bg-amber-100 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-coins text-amber-600 text-[10px]"></i>
                    </div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Pricing & Duration</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="wiz-label">Base / Display Price (per person)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-mono">
                                {{ $experience?->currency === 'USD' ? '$' : ($experience?->currency === 'EUR' ? '€' : '₹') }}
                            </span>
                            <input type="number" name="avg_price" min="0" step="0.01"
                                   value="{{ old('avg_price', $experience?->avg_price) }}"
                                   class="wiz-input pl-7 font-mono" placeholder="0.00">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Shown on listing card as "starting from" price.</p>
                    </div>

                    <div>
                        <label class="wiz-label">Default Currency</label>
                        <select name="currency" class="wiz-input">
                            @foreach($currencies as $code => $label)
                                <option value="{{ $code }}" {{ old('currency', $experience?->currency ?? 'INR') === $code ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Duration Packages --}}
                @php
                    $existingDurations = $experienceDurationPrices->map(fn($d) => [
                        'nights'      => $d->duration,
                        'price'       => $d->price ?? '',
                        'promo_price' => $d->promo_price ?? '',
                        'currency'    => $d->currency ?? 'INR',
                    ])->values()->toArray();
                    if (empty($existingDurations)) {
                        $existingDurations = [['nights' => 7, 'price' => '', 'promo_price' => '', 'currency' => old('currency', $experience?->currency ?? 'INR')]];
                    }
                @endphp
                <div x-data="durationPkgs(@json($existingDurations))" class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="wiz-label mb-0">Duration Packages</label>
                            <p class="text-[10px] text-slate-400 mt-0.5">Add one row per retreat length (e.g. 7 nights, 14 nights). These appear in booking and availability pricing.</p>
                        </div>
                        <button type="button" @click="add()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold hover:bg-amber-100 transition-all shrink-0">
                            <i class="fa-solid fa-plus text-[10px]"></i> Add
                        </button>
                    </div>

                    <div class="space-y-2">
                        <template x-for="(row, idx) in rows" :key="idx">
                            <div class="flex flex-wrap items-end gap-2 p-3 bg-slate-50 border border-slate-200 rounded-2xl">
                                <div class="w-24 shrink-0">
                                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Nights</span>
                                    <input type="number" :name="'durations[' + idx + ']'"
                                           x-model.number="row.nights"
                                           min="1" max="365" placeholder="7"
                                           class="wiz-input font-mono text-sm py-2">
                                </div>
                                <div class="flex-1 min-w-[100px]">
                                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Price (per person)</span>
                                    <input type="number" :name="'duration_price[' + idx + ']'"
                                           x-model="row.price"
                                           min="0" step="0.01" placeholder="e.g. 25000"
                                           class="wiz-input font-mono text-sm py-2">
                                </div>
                                <div class="flex-1 min-w-[100px]">
                                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Promo Price <span class="normal-case font-normal">(optional)</span></span>
                                    <input type="number" :name="'promo_price[' + idx + ']'"
                                           x-model="row.promo_price"
                                           min="0" step="0.01" placeholder="Optional"
                                           class="wiz-input font-mono text-sm py-2">
                                </div>
                                <div class="w-28 shrink-0">
                                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Currency</span>
                                    <select :name="'duration_currency[' + idx + ']'"
                                            x-model="row.currency"
                                            class="wiz-input text-sm py-2">
                                        @foreach($currencies as $code => $label)
                                        <option value="{{ $code }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="shrink-0 pb-0.5">
                                    <button type="button" @click="remove(idx)"
                                            x-show="rows.length > 1"
                                            class="h-[38px] w-8 flex items-center justify-center rounded-xl bg-rose-50 text-rose-400 hover:bg-rose-100 hover:text-rose-600 transition-all border border-rose-100">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <p class="text-[10px] text-slate-400">
                        <i class="fa-solid fa-circle-info mr-0.5"></i>
                        The smallest duration is used as the retreat's primary duration on listing pages.
                    </p>
                </div>

                <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-2xl space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-800">Online Bookable</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">Allow guests to book this retreat directly on BalanceBoat.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_bookable" value="1" class="sr-only peer"
                                   {{ old('is_bookable', $experience?->is_bookable ?? 1) ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="wiz-label">Food Type / Meal Options</label>
                    <input type="text" name="food"
                           value="{{ old('food', $experience?->food) }}"
                           class="wiz-input" placeholder="e.g., Vegetarian, Vegan, Sattvic, Organic, All-Inclusive">
                    <p class="text-[10px] text-slate-400 mt-1">Comma-separated.</p>
                </div>

                <div>
                    <label class="wiz-label">Area / Location Details</label>
                    <input type="text" name="area"
                           value="{{ old('area', $experience?->area) }}"
                           class="wiz-input" placeholder="e.g., Near Kovalam Beach, Thiruvananthapuram, Kerala">
                </div>

                <div>
                    <label class="wiz-label">Meta Title <span class="text-slate-400 font-normal">(SEO)</span></label>
                    <input type="text" name="meta_title"
                           value="{{ old('meta_title', $experience?->meta_title) }}"
                           class="wiz-input" placeholder="Leave blank to auto-fill from retreat title">
                </div>
                <div>
                    <label class="wiz-label">Meta Description <span class="text-slate-400 font-normal">(SEO)</span></label>
                    <textarea name="meta_description" rows="2" class="wiz-input resize-none"
                              placeholder="Leave blank to auto-fill from short summary">{{ old('meta_description', $experience?->meta_description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             STEP 4 — Schedule & Content
        ══════════════════════════════════════════════════════ --}}
        <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <div class="h-7 w-7 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fa-regular fa-calendar text-blue-600 text-[10px]"></i>
                    </div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Daily Schedule & Program Content</h3>
                </div>

                <div>
                    <label class="wiz-label">Daily Schedule / Timetable</label>
                    <div x-data="scheduleBuilder()" x-init="init()" class="space-y-3">
                        <div id="schedule-rows-container" class="space-y-2 border-l-4 border-blue-300 pl-4 ml-2 relative bg-white/40 p-4 rounded-2xl">
                            <template x-for="(entry, idx) in entries" :key="idx">
                                <div class="relative bg-white p-3 border border-slate-100 rounded-xl shadow-sm flex items-center justify-between text-xs hover:border-blue-300 transition-all">
                                    <input type="time" x-model="entry.time"
                                           @change="syncScheduleData()"
                                           class="font-mono text-blue-600 font-bold w-20 bg-transparent border-none outline-none text-xs focus:ring-2 focus:ring-blue-300 rounded px-1">
                                    <input type="text" x-model="entry.activity"
                                           @change="syncScheduleData()"
                                           placeholder="Activity description..."
                                           class="flex-1 font-semibold text-slate-900 px-3 bg-transparent border-none outline-none text-xs focus:ring-2 focus:ring-blue-300 rounded">
                                    <button type="button" @click="removeScheduleRow(idx)"
                                            class="text-slate-300 hover:text-red-500 transition-all">
                                        <i class="fa-solid fa-xmark text-[10px]"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="addScheduleRow()"
                                class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center space-x-1.5 pt-1 transition-all">
                            <i class="fa-solid fa-plus-circle"></i>
                            <span>Add Schedule Entry</span>
                        </button>
                        <textarea name="experience_schedule" id="scheduleDataField" class="hidden" rows="1">{{ old('experience_schedule', $experience?->schedule ?? '') }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="wiz-label">Experience Highlights</label>
                    <div x-data="highlightsBuilder()" x-init="init()" class="space-y-3">
                        <div id="highlights-rows-container" class="space-y-2 border-l-4 border-purple-300 pl-4 ml-2 relative bg-white/40 p-4 rounded-2xl">
                            <template x-for="(entry, idx) in entries" :key="idx">
                                <div class="relative bg-white p-3 border border-slate-100 rounded-xl shadow-sm flex items-center justify-between text-xs hover:border-purple-300 transition-all">
                                    <span class="text-purple-600 font-bold w-6 text-center">•</span>
                                    <input type="text" x-model="entry.text"
                                           @change="syncHighlightsData()"
                                           placeholder="Add a highlight..."
                                           class="flex-1 font-semibold text-slate-900 px-2 bg-transparent border-none outline-none text-xs focus:ring-2 focus:ring-purple-300 rounded">
                                    <button type="button" @click="removeHighlightRow(idx)"
                                            class="text-slate-300 hover:text-red-500 transition-all">
                                        <i class="fa-solid fa-xmark text-[10px]"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="addHighlightRow()"
                                class="text-xs font-semibold text-purple-600 hover:text-purple-700 flex items-center space-x-1.5 pt-1 transition-all">
                            <i class="fa-solid fa-plus-circle"></i>
                            <span>Add Highlight</span>
                        </button>
                        <p class="text-[10px] text-slate-400">Bullet points are added automatically.</p>
                        <textarea name="experience_highlights" id="highlightsDataField" class="hidden" rows="1">{{ old('experience_highlights', $experience?->experience_highlights ?? '') }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="wiz-label">Full Program Details</label>
                    <textarea name="experience_details" rows="5" class="wiz-input resize-y"
                              placeholder="Provide a detailed day-by-day breakdown, therapy descriptions, guest expectations...">{{ old('experience_details', $experience?->experience_details) }}</textarea>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-3">Inclusions & Exclusions</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="wiz-label text-emerald-700">✅ What's Included</label>
                        <textarea name="what_is_included" rows="5" class="wiz-input resize-y"
                                  placeholder="• Accommodation (7 nights)&#10;• All meals (breakfast, lunch, dinner)&#10;• Daily yoga classes&#10;• Ayurvedic consultations&#10;• Airport transfers">{{ old('what_is_included', $experience?->what_is_included) }}</textarea>
                    </div>
                    <div>
                        <label class="wiz-label text-red-600">❌ What's Not Included</label>
                        <textarea name="what_is_not_included" rows="5" class="wiz-input resize-y"
                                  placeholder="• International flights&#10;• Travel insurance&#10;• Personal expenses&#10;• Optional excursions">{{ old('what_is_not_included', $experience?->what_is_not_included) }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="wiz-label">How to Get Here</label>
                    <textarea name="how_to_get_here" rows="3" class="wiz-input resize-none"
                              placeholder="Nearest airport, recommended transport options, distances, etc.">{{ old('how_to_get_here', $experience?->how_to_get_here) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             STEP 5 — Media
        ══════════════════════════════════════════════════════ --}}
        <div x-show="currentStep === 5" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <div class="h-7 w-7 bg-rose-100 rounded-xl flex items-center justify-center">
                        <i class="fa-regular fa-image text-rose-600 text-[10px]"></i>
                    </div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Photos & Video</h3>
                </div>

                {{-- Thumbnail --}}
                <div>
                    <label class="wiz-label">Thumbnail Image <span class="text-slate-400 font-normal">(shown in search listings)</span></label>
                    @if($experience?->thumbnail_image_url)
                        <div class="mb-3">
                            <img src="{{ Storage::disk('azure')->url($experience->thumbnail_image_url) }}" alt="Current thumbnail"
                                 class="h-24 w-40 object-cover rounded-2xl border border-slate-200">
                            <p class="text-[10px] text-slate-400 mt-1">Current thumbnail. Upload a new file to replace it.</p>
                        </div>
                    @endif
                    <div class="relative border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:border-purple-400 transition-colors group cursor-pointer"
                         onclick="document.getElementById('thumbnailFile').click()">
                        <i class="fa-regular fa-image text-3xl text-slate-300 group-hover:text-purple-400 transition-colors mb-2"></i>
                        <p class="text-xs text-slate-500 font-medium">Click to upload thumbnail</p>
                        <p class="text-[10px] text-slate-400 mt-1">JPG, PNG, WebP · Max 5 MB · Recommended: 800×600 px</p>
                        <input type="file" id="thumbnailFile" name="thumbnail_image"
                               accept="image/jpeg,image/png,image/webp" class="sr-only"
                               onchange="previewImage(this, 'thumbnailPreview')">
                    </div>
                    <div id="thumbnailPreview" class="mt-3 hidden">
                        <img src="" alt="Preview" class="h-24 w-40 object-cover rounded-2xl border border-purple-200">
                    </div>
                </div>

                {{-- Banner --}}
                <div>
                    <label class="wiz-label">Banner / Hero Image <span class="text-slate-400 font-normal">(shown on the detail page header)</span></label>
                    @if($experience?->banner_image_url)
                        <div class="mb-3">
                            <img src="{{ Storage::disk('azure')->url($experience->banner_image_url) }}" alt="Current banner"
                                 class="h-24 w-64 object-cover rounded-2xl border border-slate-200">
                            <p class="text-[10px] text-slate-400 mt-1">Current banner. Upload a new file to replace.</p>
                        </div>
                    @endif
                    <div class="relative border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:border-purple-400 transition-colors group cursor-pointer"
                         onclick="document.getElementById('bannerFile').click()">
                        <i class="fa-solid fa-panorama text-3xl text-slate-300 group-hover:text-purple-400 transition-colors mb-2"></i>
                        <p class="text-xs text-slate-500 font-medium">Click to upload banner</p>
                        <p class="text-[10px] text-slate-400 mt-1">JPG, PNG, WebP · Max 10 MB · Recommended: 1600×600 px (wide)</p>
                        <input type="file" id="bannerFile" name="banner_image"
                               accept="image/jpeg,image/png,image/webp" class="sr-only"
                               onchange="previewImage(this, 'bannerPreview')">
                    </div>
                    <div id="bannerPreview" class="mt-3 hidden">
                        <img src="" alt="Preview" class="h-24 w-64 object-cover rounded-2xl border border-purple-200">
                    </div>
                </div>

                {{-- Video --}}
                <div>
                    <label class="wiz-label">Video URL <span class="text-slate-400 font-normal">(YouTube or Vimeo)</span></label>
                    <div class="relative">
                        <i class="fa-brands fa-youtube absolute left-3 top-1/2 -translate-y-1/2 text-red-400 text-sm"></i>
                        <input type="url" name="video_url"
                               value="{{ old('video_url', $experience?->video_url) }}"
                               class="wiz-input pl-8" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                </div>

                {{-- Image Gallery --}}
                <div>
                    <label class="wiz-label">Image Gallery <span class="text-slate-400 font-normal">(multiple photos of the retreat)</span></label>

                    <div class="relative border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:border-rose-400 transition-colors group cursor-pointer"
                         ondragover="event.preventDefault(); this.classList.add('border-rose-400', 'bg-rose-50')"
                         ondragleave="event.preventDefault(); this.classList.remove('border-rose-400', 'bg-rose-50')"
                         ondrop="event.preventDefault(); this.classList.remove('border-rose-400', 'bg-rose-50'); document.getElementById('galleryFiles').files = event.dataTransfer.files; previewGalleryImages(event.dataTransfer.files)"
                         onclick="document.getElementById('galleryFiles').click()">
                        <i class="fa-regular fa-images text-3xl text-slate-300 group-hover:text-rose-400 transition-colors mb-2"></i>
                        <p class="text-xs text-slate-500 font-medium">Click to upload or drag images here</p>
                        <p class="text-[10px] text-slate-400 mt-1">JPG, PNG, WebP · Max 5 MB each · Multiple files supported</p>
                        <input type="file" id="galleryFiles" name="image_galleries[]"
                               accept="image/jpeg,image/png,image/webp" multiple class="sr-only"
                               onchange="previewGalleryImages(this.files)">
                    </div>

                    {{-- Gallery Preview Grid --}}
                    <div id="galleryPreviewContainer" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        {{-- Existing images from database --}}
                        @if(isset($experience) && $experience->image_galleries)
                            @foreach($experience->image_galleries as $img)
                                <div class="relative aspect-square bg-slate-100 rounded-2xl overflow-hidden shadow-sm">
                                    <img src="{{ Storage::disk('azure')->url($img->image_url) }}" alt="Gallery Image" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="button" onclick="removeExistingGalleryImage(this, '{{ $img->id }}')"
                                                class="text-white text-xs font-semibold px-2 py-1 bg-red-600 rounded">
                                            <i class="fa-solid fa-trash mr-1"></i>Remove
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <p class="text-[10px] text-slate-400 mt-2">Selected images will be uploaded when you submit the form.</p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             STEP 6 — Policies & Publish
        ══════════════════════════════════════════════════════ --}}
        <div x-show="currentStep === 6" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-5">

            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="flex items-center space-x-2 border-b border-slate-100 pb-3">
                    <div class="h-7 w-7 bg-slate-100 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-slate-600 text-[10px]"></i>
                    </div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Booking Policies</h3>
                </div>

                {{-- Deposit Policy --}}
                <div class="space-y-3">
                    <label class="wiz-label">Deposit Policy</label>
                    @php $depositPolicy = old('deposit_policy', $experience?->deposit_policy ?? 1); @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        @foreach([1 => 'Full Payment Upfront', 2 => 'Fixed Deposit Amount', 3 => 'Percentage Deposit'] as $val => $label)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="deposit_policy" value="{{ $val }}"
                                       {{ (int)$depositPolicy === $val ? 'checked' : '' }}
                                       class="sr-only peer"
                                       @change="depositPolicy = $event.target.value">
                                <div class="p-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-xs text-center font-medium text-slate-600 cursor-pointer transition-all peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-700">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div x-show="depositPolicy !== '1'" class="mt-2">
                        <label class="wiz-label">Deposit Amount / Percentage</label>
                        <input type="number" name="deposit_amount" min="0"
                               value="{{ old('deposit_amount', $experience?->deposit_amount) }}"
                               class="wiz-input max-w-xs font-mono" placeholder="e.g., 5000 or 20">
                    </div>
                </div>

                {{-- Cancellation Policy --}}
                <div class="space-y-3">
                    <label class="wiz-label">Cancellation Policy</label>
                    @php $cancelCondition = old('cancellation_policy_condition', $experience?->cancellation_policy_condition ?? 1); @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        @foreach([1 => 'Non-Refundable', 2 => 'Always Refundable', 3 => 'Refundable Before X Days'] as $val => $label)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="cancellation_policy_condition" value="{{ $val }}"
                                       {{ (int)$cancelCondition === $val ? 'checked' : '' }}
                                       class="sr-only peer"
                                       @change="cancelCondition = $event.target.value">
                                <div class="p-3 bg-slate-50 border-2 border-slate-200 rounded-2xl text-xs text-center font-medium text-slate-600 cursor-pointer transition-all peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-700">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div x-show="cancelCondition === '3'" class="mt-2">
                        <label class="wiz-label">Days Before Arrival</label>
                        <input type="number" name="cancellation_policy_days" min="1"
                               value="{{ old('cancellation_policy_days', $experience?->cancellation_policy_days) }}"
                               class="wiz-input max-w-xs font-mono" placeholder="e.g., 14">
                    </div>
                    <div>
                        <label class="wiz-label">Cancellation Policy Details</label>
                        <textarea name="cancellation_policy" rows="3" class="wiz-input resize-none"
                                  placeholder="Describe your full cancellation and refund terms in plain language...">{{ old('cancellation_policy', $experience?->cancellation_policy) }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="wiz-label">Booking Information / Instructions</label>
                    <textarea name="booking_info" rows="3" class="wiz-input resize-none"
                              placeholder="Payment methods accepted, what to bring, arrival process, etc.">{{ old('booking_info', $experience?->booking_info) }}</textarea>
                </div>
            </div>

            {{-- Publish Panel --}}
            <div class="bg-gradient-to-tr from-purple-50 to-indigo-50 border border-purple-100 rounded-3xl p-6 shadow-sm space-y-5">
                <div class="flex items-center space-x-2 border-b border-purple-100 pb-3">
                    <i class="fa-solid fa-rocket text-purple-600 text-sm"></i>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Publish Settings</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="is_draft" value="0"
                               {{ old('is_draft', $experience?->is_draft ?? 1) == 0 ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="p-4 bg-white border-2 border-slate-200 rounded-2xl transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50">
                            <div class="flex items-center space-x-3">
                                <div class="h-9 w-9 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-globe text-emerald-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900">Publish Live</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Visible to all travellers on BalanceBoat immediately.</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="is_draft" value="1"
                               {{ old('is_draft', $experience?->is_draft ?? 1) == 1 ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="p-4 bg-white border-2 border-slate-200 rounded-2xl transition-all peer-checked:border-amber-400 peer-checked:bg-amber-50">
                            <div class="flex items-center space-x-3">
                                <div class="h-9 w-9 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-pen-to-square text-amber-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900">Save as Draft</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Save privately. You can publish later from your dashboard.</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                {{-- Final submit --}}
                <div class="flex items-center justify-between pt-2">
                    <p class="text-[11px] text-slate-500">
                        Review all steps before submitting.
                        <a href="{{ route('center-panel.experiences') }}" class="text-red-500 hover:underline ml-1">Cancel</a>
                    </p>
                    <button type="button" id="finalSubmitBtn" @click="submit()"
                            class="py-3 px-8 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold transition-all flex items-center space-x-2 shadow-md hover:scale-[1.01]">
                        <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                        <span>{{ $experience ? 'Update Retreat' : 'Create Retreat' }}</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Step Navigation Footer ─────────────────────────────── --}}
        <div class="flex items-center justify-between" x-show="currentStep <= 6">
            <button type="button" @click="prev()"
                    x-show="currentStep > 1"
                    class="py-2.5 px-5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-2xl text-xs font-semibold transition-all flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Previous</span>
            </button>
            <div x-show="currentStep === 1"></div>

            <div class="flex items-center space-x-3">
                <span class="text-[10px] text-slate-400 font-mono">Step <span x-text="currentStep"></span> of 6</span>
                <button type="button" @click="next()"
                        x-show="currentStep < 6"
                        class="py-2.5 px-6 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold transition-all flex items-center space-x-2 shadow-md">
                    <span>Next Step</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Inline styles scoped to this form --}}
<style>
    .wiz-label {
        display: block;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #64748b;
        margin-bottom: 6px;
    }
    .wiz-input {
        width: 100%;
        padding: 10px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 13px;
        color: #1e293b;
        transition: border-color 0.2s, background 0.2s;
        box-sizing: border-box;
        font-family: inherit;
    }
    .wiz-input:focus {
        outline: none;
        border-color: #a855f7;
        background: #ffffff;
    }
    .wiz-err { color: #ef4444; font-size: 11px; margin-top: 4px; }

    /* Chip-style checkbox / radio */
    .wiz-chip-label input:checked + .wiz-chip {
        background: rgba(168, 85, 247, 0.12);
        border-color: #a855f7;
        color: #7e22ce;
        font-weight: 600;
    }
    .wiz-chip {
        display: inline-flex;
        align-items: center;
        padding: 5px 13px;
        border: 1.5px solid #e2e8f0;
        border-radius: 100px;
        font-size: 11.5px;
        color: #475569;
        cursor: pointer;
        transition: all 0.18s ease;
        background: #f8fafc;
        white-space: nowrap;
    }
    .wiz-chip:hover { border-color: #a855f7; color: #7e22ce; }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('wizardApp', () => ({
        currentStep: 1,
        depositPolicy: '{{ old('deposit_policy', $experience?->deposit_policy ?? 1) }}',
        cancelCondition: '{{ old('cancellation_policy_condition', $experience?->cancellation_policy_condition ?? 1) }}',
        errors: {},

        // Which fields are required in each step (field name → label)
        _stepRules: {
            1: { name: 'Retreat title' },
        },

        init() {
            document.querySelectorAll('input[name="deposit_policy"]').forEach(r => {
                r.addEventListener('change', () => this.depositPolicy = r.value);
            });
            document.querySelectorAll('input[name="cancellation_policy_condition"]').forEach(r => {
                r.addEventListener('change', () => this.cancelCondition = r.value);
            });
        },

        clearError(field) {
            const updated = { ...this.errors };
            delete updated[field];
            this.errors = updated;
        },

        // Validate only the current step's required fields
        validateCurrentStep() {
            const rules = this._stepRules[this.currentStep] || {};
            const newErrors = {};
            for (const [fieldName, label] of Object.entries(rules)) {
                const el = document.querySelector(`[name="${fieldName}"]`);
                if (!el || !el.value.trim()) {
                    newErrors[fieldName] = `${label} is required.`;
                }
            }
            this.errors = { ...this.errors, ...newErrors };
            return Object.keys(newErrors).length === 0;
        },

        // Validate all steps before final submit; jump to first step with an error
        validateAll() {
            const allErrors = {};
            let firstErrorStep = null;
            for (let step = 1; step <= 6; step++) {
                const rules = this._stepRules[step] || {};
                for (const [fieldName, label] of Object.entries(rules)) {
                    const el = document.querySelector(`[name="${fieldName}"]`);
                    if (!el || !el.value.trim()) {
                        allErrors[fieldName] = `${label} is required.`;
                        if (firstErrorStep === null) firstErrorStep = step;
                    }
                }
            }
            this.errors = allErrors;
            if (firstErrorStep !== null) {
                this.currentStep = firstErrorStep;
                this.$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }));
                return false;
            }
            return true;
        },

        goTo(step) {
            if (step >= 1 && step <= 6) this.currentStep = step;
        },

        next() {
            if (!this.validateCurrentStep()) {
                this.$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }));
                return;
            }
            if (this.currentStep < 6) {
                this.currentStep++;
                this.$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }));
            }
        },

        prev() {
            if (this.currentStep > 1) {
                this.currentStep--;
                this.$nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }));
            }
        },

        submit() {
            if (this.validateAll()) {
                document.getElementById('experienceForm').submit();
            }
        }
    }));
});

// ══════════════════════════════════════════════════════════════
// DURATION PACKAGES — Dynamic multi-row duration manager
// ══════════════════════════════════════════════════════════════
function durationPkgs(initial) {
    return {
        rows: initial && initial.length ? initial : [{ nights: 7, price: '', promo_price: '', currency: 'INR' }],
        add() {
            this.rows.push({ nights: '', price: '', promo_price: '', currency: this.rows[this.rows.length - 1]?.currency ?? 'INR' });
        },
        remove(idx) {
            if (this.rows.length > 1) this.rows.splice(idx, 1);
        },
    };
}

// ══════════════════════════════════════════════════════════════
// SCHEDULE BUILDER — Interactive timeline for daily schedule
// ══════════════════════════════════════════════════════════════
function scheduleBuilder() {
    return {
        entries: [],

        init() {
            const dataField = document.getElementById('scheduleDataField');
            if (dataField && dataField.value.trim()) {
                this.entries = this.parseScheduleData(dataField.value);
            } else {
                this.entries = [{ time: '06:00', activity: 'Morning Activity' }];
            }
        },

        parseScheduleData(text) {
            return text.split('\n').filter(l => l.trim()).map(line => {
                const match = line.match(/^(\d{2}:\d{2})\s+(.+)$/);
                if (match) {
                    return { time: match[1], activity: match[2] };
                }
                return { time: '12:00', activity: line.trim() };
            });
        },

        addScheduleRow() {
            this.entries.push({ time: '12:00', activity: 'New Activity' });
            this.$nextTick(() => this.syncScheduleData());
        },

        removeScheduleRow(idx) {
            this.entries.splice(idx, 1);
            this.$nextTick(() => this.syncScheduleData());
        },

        syncScheduleData() {
            const text = this.entries.map(e => `${e.time}  ${e.activity}`).join('\n');
            document.getElementById('scheduleDataField').value = text;
        }
    };
}

// ══════════════════════════════════════════════════════════════
// HIGHLIGHTS BUILDER — Interactive bullet-point list
// ══════════════════════════════════════════════════════════════
function highlightsBuilder() {
    return {
        entries: [],

        init() {
            const dataField = document.getElementById('highlightsDataField');
            if (dataField && dataField.value.trim()) {
                this.entries = this.parseHighlightsData(dataField.value);
            } else {
                this.entries = [{ text: 'Expert guidance from experienced instructors' }];
            }
        },

        parseHighlightsData(text) {
            return text.split('\n')
                .filter(l => l.trim())
                .map(line => ({ text: line.replace(/^•\s*/, '').trim() }));
        },

        addHighlightRow() {
            this.entries.push({ text: 'New highlight' });
            this.$nextTick(() => this.syncHighlightsData());
        },

        removeHighlightRow(idx) {
            this.entries.splice(idx, 1);
            this.$nextTick(() => this.syncHighlightsData());
        },

        syncHighlightsData() {
            const text = this.entries.map(e => `• ${e.text}`).join('\n');
            document.getElementById('highlightsDataField').value = text;
        }
    };
}
function autoSlug() {
    const name = document.getElementById('field_name').value;
    const slugField = document.getElementById('field_slug');
    if (!slugField._manuallyEdited) {
        slugField.value = name
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }
}
document.getElementById('field_slug').addEventListener('input', function() {
    this._manuallyEdited = !!this.value;
});

// Image preview
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Gallery preview handler for multiple images
let galleryFilesStore = [];

function previewGalleryImages(fileList) {
    const container = document.getElementById('galleryPreviewContainer');
    galleryFilesStore = Array.from(fileList);

    container.innerHTML = galleryFilesStore.map((file, idx) => {
        const reader = new FileReader();
        const previewDiv = document.createElement('div');

        reader.onload = e => {
            previewDiv.innerHTML = `
                <div class="relative aspect-square bg-slate-100 rounded-2xl overflow-hidden group shadow-sm">
                    <img src="${e.target.result}" alt="Gallery preview" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                        <button type="button" onclick="removeGalleryImage(${idx})" class="text-white text-xs font-semibold">
                            <i class="fa-solid fa-trash mr-1"></i>Remove
                        </button>
                    </div>
                </div>
            `;
        };
        reader.readAsDataURL(file);

        return previewDiv;
    }).reduce((fragment, div) => {
        const wrapper = document.createElement('div');
        const clone = div.cloneNode(true);
        wrapper.innerHTML = clone.innerHTML;
        container.appendChild(wrapper);
        return fragment;
    }, null);
}

function removeGalleryImage(idx) {
    galleryFilesStore.splice(idx, 1);
    document.getElementById('galleryFiles').value = '';

    // Create a new DataTransfer object with remaining files
    const dataTransfer = new DataTransfer();
    galleryFilesStore.forEach(file => dataTransfer.items.add(file));
    document.getElementById('galleryFiles').files = dataTransfer.files;

    previewGalleryImages(galleryFilesStore);
}
</script>

@endsection
