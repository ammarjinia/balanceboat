@extends('layouts.center')

@section('title', 'Availability & Pricing — ' . $experience->name)

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
<style>
    .bb-serif { font-family: 'Playfair Display', serif; }
    .bb-sans  { font-family: 'Outfit', sans-serif; }

    .glass-forest {
        background: rgba(255,255,255,0.78);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid rgba(47,111,87,0.12);
        box-shadow: 0 3px 20px -5px rgba(15,23,42,0.07);
    }
    .price-card {
        background: rgba(248,250,248,0.9);
        border: 1px solid rgba(47,111,87,0.12);
        border-radius: 18px;
        transition: border-color 0.2s;
    }
    .price-card:hover { border-color: rgba(47,111,87,0.3); }

    .fi {   /* form input */
        background: #fff;
        border: 1px solid rgba(47,111,87,0.18);
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 12px;
        width: 100%;
        font-family: 'Outfit', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s;
        color: #1E2522;
    }
    .fi:focus {
        outline: none;
        border-color: #2F6F57;
        box-shadow: 0 0 0 3px rgba(47,111,87,0.1);
    }
    .fi::placeholder { color: #94a3b8; }
    select.fi { cursor: pointer; }

    .price-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748B;
        display: block;
        margin-bottom: 4px;
        font-family: 'Outfit', sans-serif;
    }
    .occupancy-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        padding: 2px 7px;
        border-radius: 6px;
    }

    [x-cloak] { display: none !important; }
</style>
@endsection

@section('content')

    {{-- ── Breadcrumb + Header ──────────────────────────────── --}}
    <div class="border-b border-[#2F6F57]/10 pb-6 space-y-3 bb-sans">
        <nav class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('center-panel.availability') }}" class="hover:text-[#2F6F57] transition-colors">Availability & Pricing</a>
            <i class="fa-solid fa-chevron-right text-[9px]"></i>
            <span class="text-[#1E2522] font-semibold">{{ $experience->name }}</span>
        </nav>
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-[#2F6F57] mb-1">Experience Pricing Configuration</p>
            <h1 class="bb-serif text-3xl font-medium text-[#1E2522]">{{ $experience->name }}</h1>
            <p class="text-[#64748B] text-sm mt-1 font-light">
                Toggle accommodations on/off, set base rates per occupancy type, then add date-range seasonal overrides.
            </p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-emerald-700 bb-sans">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-rose-50 border border-rose-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-rose-700 bb-sans">
            <i class="fa-solid fa-circle-exclamation text-rose-500"></i> {{ session('error') }}
        </div>
    @endif

    @if($centerAccommodations->isEmpty())
        <div class="glass-forest rounded-3xl py-16 flex flex-col items-center text-center px-6 bb-sans">
            <div class="w-16 h-16 rounded-3xl bg-amber-50 border border-amber-100 flex items-center justify-center mb-4">
                <i class="fa-regular fa-bed text-2xl text-amber-400"></i>
            </div>
            <h3 class="bb-serif text-xl font-medium text-[#1E2522] mb-1">No accommodations linked</h3>
            <p class="text-xs text-[#64748B] max-w-xs mb-5 font-light leading-relaxed">
                Add accommodations to your center first before configuring pricing for this experience.
            </p>
            <a href="{{ route('center-panel.accommodation.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#2F6F57] text-white rounded-xl text-xs font-semibold hover:bg-[#255a46] transition-all">
                <i class="fa-solid fa-plus"></i> Add Accommodation
            </a>
        </div>
    @else

    <form id="availability-form"
          method="POST"
          action="{{ route('center-panel.availability.save', $experience->id) }}">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

            {{-- ── Left Column: Accordion Cards ────────────────── --}}
            <div class="xl:col-span-8 space-y-4">

                {{-- Occupancy legend --}}
                <div class="glass-forest rounded-2xl px-5 py-4 flex flex-wrap items-center gap-4 bb-sans">
                    <p class="text-xs font-bold text-[#1E2522] uppercase tracking-wide shrink-0">Pricing Structure:</p>
                    <div class="flex flex-wrap gap-3">
                        <span class="occupancy-badge bg-[#2F6F57]/10 text-[#2F6F57]">
                            <i class="fa-solid fa-user text-[8px]"></i> Default / Per Guest
                        </span>
                        <span class="occupancy-badge bg-amber-50 text-amber-700">
                            <i class="fa-solid fa-person text-[8px]"></i> Single Room — solo occupant
                        </span>
                        <span class="occupancy-badge bg-blue-50 text-blue-700">
                            <i class="fa-solid fa-user-group text-[8px]"></i> Double — per person when 2 share
                        </span>
                    </div>
                    <p class="text-[11px] text-[#64748B] font-light w-full mt-0.5">Leave Single/Double blank to use the Default rate for all occupancies.</p>
                </div>

                {{-- Accommodation Accordion Cards --}}
                @foreach($centerAccommodations as $accom)
                @php
                    $ea         = $existingEA[$accom->id] ?? null;
                    $isIncluded = !is_null($ea);
                    $isDefault  = $ea && $ea->accomodation_default == 1;
                    $prices     = $existingPrices[$accom->id] ?? collect();
                @endphp

                <div x-data="{
                        open:     {{ $isIncluded ? 'true' : 'false' }},
                        included: {{ $isIncluded ? 'true' : 'false' }}
                     }"
                     class="glass-forest rounded-3xl overflow-hidden transition-all duration-200 bb-sans"
                     :class="{ 'ring-2 ring-[#2F6F57]/30': included }">

                    {{-- Card Header --}}
                    <div class="flex items-center gap-4 px-5 py-4 cursor-pointer select-none"
                         @click="if (included) open = !open">

                        {{-- Toggle --}}
                        <label class="relative inline-flex items-center cursor-pointer shrink-0" @click.stop>
                            <input type="checkbox"
                                   x-model="included"
                                   name="accommodations[{{ $accom->id }}][included]"
                                   value="1"
                                   @change="if ($event.target.checked) { open = true }"
                                   class="sr-only peer">
                            <div class="relative w-10 h-6 rounded-full transition-colors duration-200
                                        bg-slate-200 peer-checked:bg-[#2F6F57]
                                        after:content-[''] after:absolute after:top-[3px] after:left-[3px]
                                        after:w-[18px] after:h-[18px] after:bg-white after:rounded-full
                                        after:shadow-sm after:transition-all after:duration-200
                                        peer-checked:after:translate-x-4 peer"></div>
                        </label>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-semibold text-sm text-[#1E2522]">{{ $accom->name }}</span>
                                @if($accom->max_guest_in_room)
                                <span class="occupancy-badge bg-slate-100 text-[#64748B]">
                                    <i class="fa-solid fa-user text-[8px]"></i> {{ $accom->max_guest_in_room }} max
                                </span>
                                @endif
                                @if($isDefault)
                                <span class="occupancy-badge bg-[#D4AF37]/15 text-amber-700">
                                    <i class="fa-solid fa-star text-[8px]"></i> Default
                                </span>
                                @endif
                                @if($prices->count())
                                <span class="occupancy-badge bg-[#2F6F57]/10 text-[#2F6F57]">
                                    {{ $prices->count() }} seasonal {{ $prices->count() == 1 ? 'range' : 'ranges' }}
                                </span>
                                @endif
                            </div>
                            @if($ea)
                            <p class="text-[11px] text-[#64748B] font-light mt-0.5">
                                Base {{ $ea->currency }}
                                {{ $ea->price_per_night_per_guest ? number_format($ea->price_per_night_per_guest, 0) : '—' }}/night
                                @if($ea->single_occupancy_price) · Single {{ number_format($ea->single_occupancy_price, 0) }} @endif
                                @if($ea->double_occupancy_price) · Double {{ number_format($ea->double_occupancy_price, 0) }}/ea @endif
                            </p>
                            @else
                            <p class="text-[11px] text-[#64748B] font-light mt-0.5" x-show="!included">Toggle on to configure pricing</p>
                            @endif
                        </div>

                        {{-- Chevron --}}
                        <div class="shrink-0 w-7 h-7 rounded-lg flex items-center justify-center"
                             :class="included ? 'bg-[#2F6F57]/8' : ''">
                            <i class="fa-solid fa-chevron-down text-[#64748B] text-[10px] transition-transform duration-200"
                               :class="{ 'rotate-180': open && included }"></i>
                        </div>
                    </div>

                    {{-- ── Card Body ─────────────────────────────── --}}
                    <div x-show="open && included"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="border-t border-[#2F6F57]/10 px-5 pb-5 pt-4 space-y-5">

                        @if($ea)
                        <input type="hidden" name="accommodations[{{ $accom->id }}][ea_id]" value="{{ $ea->id }}">
                        @endif

                        {{-- ── Base Rates ─────────────────────────────── --}}
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-bold text-[#1E2522] uppercase tracking-wide">Base Rates</h4>
                                <span class="text-[10px] text-[#64748B] font-light">Used when no date range overrides apply</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">

                                {{-- Default per guest --}}
                                <div class="sm:col-span-3">
                                    <span class="price-label">
                                        <i class="fa-solid fa-user text-[8px] mr-0.5 text-[#2F6F57]"></i>
                                        Default / Guest
                                    </span>
                                    <input type="number" class="fi"
                                           name="accommodations[{{ $accom->id }}][base_price]"
                                           value="{{ old('accommodations.'.$accom->id.'.base_price', $ea->price_per_night_per_guest ?? '') }}"
                                           min="0" step="0.01" placeholder="0.00">
                                </div>

                                {{-- Single occupancy --}}
                                <div class="sm:col-span-3">
                                    <span class="price-label">
                                        <i class="fa-solid fa-person text-[8px] mr-0.5 text-amber-600"></i>
                                        Single Room
                                    </span>
                                    <input type="number" class="fi"
                                           name="accommodations[{{ $accom->id }}][single_base_price]"
                                           value="{{ old('accommodations.'.$accom->id.'.single_base_price', $ea->single_occupancy_price ?? '') }}"
                                           min="0" step="0.01" placeholder="Optional">
                                </div>

                                {{-- Double occupancy --}}
                                <div class="sm:col-span-3">
                                    <span class="price-label">
                                        <i class="fa-solid fa-user-group text-[8px] mr-0.5 text-blue-600"></i>
                                        Double (each)
                                    </span>
                                    <input type="number" class="fi"
                                           name="accommodations[{{ $accom->id }}][double_base_price]"
                                           value="{{ old('accommodations.'.$accom->id.'.double_base_price', $ea->double_occupancy_price ?? '') }}"
                                           min="0" step="0.01" placeholder="Optional">
                                </div>

                                {{-- Currency --}}
                                <div class="sm:col-span-2">
                                    <span class="price-label">Currency</span>
                                    <select class="fi" name="accommodations[{{ $accom->id }}][currency]">
                                        @foreach($currencies as $code => $label)
                                            <option value="{{ $code }}" {{ ($ea->currency ?? 'INR') === $code ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Default radio --}}
                                <div class="sm:col-span-1 flex items-end pb-1">
                                    <label class="flex flex-col items-center gap-1 cursor-pointer group">
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-[#64748B] group-hover:text-amber-600 transition-colors text-center">Default Room</span>
                                        <input type="radio"
                                               name="default_accommodation"
                                               value="{{ $accom->id }}"
                                               {{ $isDefault ? 'checked' : '' }}
                                               class="w-4 h-4 accent-amber-500">
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- ── Seasonal Pricing Cards ──────────────────── --}}
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-xs font-bold text-[#1E2522] uppercase tracking-wide">Seasonal Date-Range Overrides</h4>
                                    <p class="text-[11px] text-[#64748B] font-light mt-0.5">
                                        Override base rates for specific periods. Leave a price blank to inherit the base rate.
                                    </p>
                                </div>
                                <button type="button"
                                        onclick="addPriceCard({{ $accom->id }})"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#2F6F57]/10 text-[#2F6F57] rounded-xl text-xs font-bold hover:bg-[#2F6F57]/20 active:scale-95 transition-all shrink-0">
                                    <i class="fa-solid fa-plus text-[10px]"></i> Add Range
                                </button>
                            </div>

                            {{-- Empty notice --}}
                            <div id="empty-notice-{{ $accom->id }}"
                                 class="{{ $prices->isEmpty() ? '' : 'hidden' }} rounded-2xl border border-dashed border-[#2F6F57]/20 bg-[#2F6F57]/3 py-6 text-center">
                                <i class="fa-regular fa-calendar-plus text-2xl text-[#2F6F57]/30 mb-2 block"></i>
                                <p class="text-xs text-[#64748B] font-light">No seasonal overrides yet.</p>
                                <p class="text-[11px] text-[#64748B] font-light">Click <strong class="text-[#2F6F57]">Add Range</strong> to set peak / off-peak rates.</p>
                            </div>

                            {{-- Existing price cards --}}
                            <div id="cards-container-{{ $accom->id }}" class="space-y-3">
                                @foreach($prices as $price)
                                <div class="price-card p-4 space-y-3" id="price-card-{{ $price->id }}">

                                    {{-- Row 1: Dates + Duration + Remove --}}
                                    <div class="flex flex-wrap items-end gap-3">
                                        <input type="hidden"
                                               name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][price_id]"
                                               value="{{ $price->id }}">

                                        <div class="flex-1 min-w-[130px]">
                                            <span class="price-label">Start Date</span>
                                            <input type="date" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][start_date]"
                                                   value="{{ $price->start_date }}">
                                        </div>

                                        <div class="flex-1 min-w-[130px]">
                                            <span class="price-label">End Date</span>
                                            <input type="date" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][end_date]"
                                                   value="{{ $price->end_date }}">
                                        </div>

                                        <div class="w-24">
                                            <span class="price-label" title="Price applies only to bookings of this many nights (leave blank for all durations)">
                                                Nights Filter <i class="fa-solid fa-circle-question text-[8px]"></i>
                                            </span>
                                            <input type="number" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][duration]"
                                                   value="{{ $price->duration }}"
                                                   min="1" placeholder="Any">
                                        </div>

                                        <div class="shrink-0 pb-0.5">
                                            <button type="button"
                                                    onclick="deleteExistingCard({{ $price->id }}, 'price-card-{{ $price->id }}', {{ $accom->id }})"
                                                    class="h-[34px] w-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-400 hover:bg-rose-100 hover:text-rose-600 transition-all"
                                                    title="Remove this date range">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Row 2: Occupancy Prices --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 border-t border-[#2F6F57]/8">
                                        <div>
                                            <span class="price-label">
                                                <i class="fa-solid fa-user text-[8px] mr-0.5 text-[#2F6F57]"></i>
                                                Default / Guest
                                            </span>
                                            <input type="number" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][price]"
                                                   value="{{ $price->price_per_night_per_guest }}"
                                                   min="0" step="0.01" placeholder="Inherit base">
                                        </div>
                                        <div>
                                            <span class="price-label">
                                                <i class="fa-solid fa-person text-[8px] mr-0.5 text-amber-600"></i>
                                                Single Room
                                            </span>
                                            <input type="number" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][single_occupancy_price]"
                                                   value="{{ $price->single_occupancy_price }}"
                                                   min="0" step="0.01" placeholder="Inherit base">
                                        </div>
                                        <div>
                                            <span class="price-label">
                                                <i class="fa-solid fa-user-group text-[8px] mr-0.5 text-blue-600"></i>
                                                Double (each)
                                            </span>
                                            <input type="number" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][double_occupancy_price]"
                                                   value="{{ $price->double_occupancy_price }}"
                                                   min="0" step="0.01" placeholder="Inherit base">
                                        </div>
                                    </div>

                                    {{-- Row 3: Promo --}}
                                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-[#2F6F57]/8">
                                        <div>
                                            <span class="price-label">Promo Price <span class="normal-case font-normal tracking-normal">(overrides all above)</span></span>
                                            <input type="number" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][promo_price]"
                                                   value="{{ $price->promotional_price }}"
                                                   min="0" step="0.01" placeholder="Optional">
                                        </div>
                                        <div>
                                            <span class="price-label">Discount Label</span>
                                            <input type="text" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][promo_discount]"
                                                   value="{{ $price->promotional_discount }}"
                                                   placeholder="e.g. 10% off, Early Bird">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Template for new cards (hidden) --}}
                            <template id="card-template-{{ $accom->id }}">
                                <div class="price-card p-4 space-y-3 new-price-card">
                                    <div class="flex flex-wrap items-end gap-3">
                                        <div class="flex-1 min-w-[130px]">
                                            <span class="price-label">Start Date</span>
                                            <input type="date" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][start_date]">
                                        </div>
                                        <div class="flex-1 min-w-[130px]">
                                            <span class="price-label">End Date</span>
                                            <input type="date" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][end_date]">
                                        </div>
                                        <div class="w-24">
                                            <span class="price-label" title="Leave blank to apply to all retreat durations">Nights Filter</span>
                                            <input type="number" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][duration]"
                                                   min="1" placeholder="Any">
                                        </div>
                                        <div class="shrink-0 pb-0.5">
                                            <button type="button"
                                                    onclick="removeNewCard(this, {{ $accom->id }})"
                                                    class="h-[34px] w-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-400 hover:bg-rose-100 hover:text-rose-600 transition-all">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 border-t border-[#2F6F57]/8">
                                        <div>
                                            <span class="price-label"><i class="fa-solid fa-user text-[8px] mr-0.5 text-[#2F6F57]"></i> Default / Guest</span>
                                            <input type="number" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][price]"
                                                   min="0" step="0.01" placeholder="Inherit base">
                                        </div>
                                        <div>
                                            <span class="price-label"><i class="fa-solid fa-person text-[8px] mr-0.5 text-amber-600"></i> Single Room</span>
                                            <input type="number" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][single_occupancy_price]"
                                                   min="0" step="0.01" placeholder="Inherit base">
                                        </div>
                                        <div>
                                            <span class="price-label"><i class="fa-solid fa-user-group text-[8px] mr-0.5 text-blue-600"></i> Double (each)</span>
                                            <input type="number" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][double_occupancy_price]"
                                                   min="0" step="0.01" placeholder="Inherit base">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-[#2F6F57]/8">
                                        <div>
                                            <span class="price-label">Promo Price <span class="normal-case font-normal tracking-normal">(overrides all above)</span></span>
                                            <input type="number" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][promo_price]"
                                                   min="0" step="0.01" placeholder="Optional">
                                        </div>
                                        <div>
                                            <span class="price-label">Discount Label</span>
                                            <input type="text" class="fi"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][promo_discount]"
                                                   placeholder="e.g. 10% off, Early Bird">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                    </div>{{-- /card body --}}
                </div>{{-- /accordion --}}
                @endforeach

            </div>{{-- /left col --}}

            {{-- ── Right Column: Sticky Sidebar ────────────────── --}}
            <div class="xl:col-span-4">
                <div class="sticky top-6 space-y-4 bb-sans">

                    {{-- Experience card --}}
                    <div class="glass-forest rounded-3xl p-5 space-y-4">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#64748B]">Experience</h3>
                        <div class="flex items-start gap-3">
                            @if($experience->thumbnail_image_url)
                            <img src="{{ Storage::disk('azure')->url($experience->thumbnail_image_url) }}"
                                 alt="{{ $experience->name }}"
                                 class="w-14 h-14 rounded-2xl object-cover shrink-0">
                            @else
                            <div class="w-14 h-14 rounded-2xl bg-[#2F6F57]/8 border border-[#2F6F57]/15 flex items-center justify-center shrink-0">
                                <i class="fa-regular fa-spa text-[#2F6F57]/30 text-xl"></i>
                            </div>
                            @endif
                            <div class="min-w-0">
                                <p class="bb-serif text-base font-medium text-[#1E2522] leading-tight">{{ $experience->name }}</p>
                                @if($experience->experience_summary)
                                <p class="text-[11px] text-[#64748B] font-light mt-0.5 line-clamp-2 leading-relaxed">
                                    {{ Str::limit(strip_tags($experience->experience_summary), 75) }}
                                </p>
                                @endif
                            </div>
                        </div>

                        @php
                            $includedCount   = $existingEA->count();
                            $priceRangeCount = $existingPrices->flatten()->count();
                        @endphp
                        <div class="grid grid-cols-2 gap-3 pt-1 border-t border-[#2F6F57]/8">
                            <div class="bg-[#2F6F57]/6 rounded-2xl p-3 text-center">
                                <p class="bb-serif text-xl font-semibold text-[#2F6F57]">{{ $includedCount }}</p>
                                <p class="text-[10px] text-[#2F6F57] font-medium mt-0.5">Room Types</p>
                            </div>
                            <div class="bg-[#D4AF37]/10 rounded-2xl p-3 text-center">
                                <p class="bb-serif text-xl font-semibold text-amber-700">{{ $priceRangeCount }}</p>
                                <p class="text-[10px] text-amber-700 font-medium mt-0.5">Price Ranges</p>
                            </div>
                        </div>
                    </div>

                    {{-- Save card --}}
                    <div class="glass-forest rounded-3xl p-5 space-y-3">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#64748B]">Save Configuration</h3>
                        <p class="text-[11px] text-[#64748B] font-light leading-relaxed">
                            Toggled-off accommodations will be removed from this experience along with all their price ranges.
                        </p>
                        <button type="submit"
                                class="w-full py-3 bg-[#2F6F57] text-white rounded-2xl text-sm font-bold hover:bg-[#255a46] active:scale-95 transition-all flex items-center justify-center gap-2 shadow-sm shadow-[#2F6F57]/20">
                            <i class="fa-solid fa-floppy-disk"></i> Save Availability
                        </button>
                        <a href="{{ route('center-panel.availability') }}"
                           class="w-full py-2.5 border border-[#2F6F57]/20 text-[#64748B] rounded-2xl text-xs font-semibold hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-arrow-left text-[10px]"></i> Back to Experiences
                        </a>
                    </div>

                    {{-- How pricing works --}}
                    <div class="rounded-3xl border border-[#2F6F57]/15 bg-[#2F6F57]/4 p-4 space-y-3">
                        <p class="text-xs font-bold text-[#2F6F57] flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info"></i> How Pricing Works
                        </p>
                        <ul class="text-[11px] text-[#2F6F57]/80 space-y-2 leading-relaxed">
                            <li class="flex items-start gap-2">
                                <span class="occupancy-badge bg-[#2F6F57]/10 text-[#2F6F57] shrink-0 mt-0.5">Default</span>
                                <span>Fallback rate per guest. Used when no occupancy-specific price is set.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="occupancy-badge bg-amber-50 text-amber-700 shrink-0 mt-0.5">Single</span>
                                <span>Charged when one guest occupies a room alone (single supplement).</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="occupancy-badge bg-blue-50 text-blue-700 shrink-0 mt-0.5">Double</span>
                                <span>Per-person rate when 2 guests share. Each guest pays this amount.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-calendar-days text-[#2F6F57]/50 text-xs shrink-0 mt-0.5"></i>
                                <span><strong>Nights Filter</strong> — set a number to apply a range only when the booking duration matches that many nights.</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>{{-- /grid --}}
    </form>

    @endif

@endsection

@section('scripts')
<script>
(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    /* ── Add new price card ──────────────────────────────────────────── */
    window.addPriceCard = function (accomId) {
        const template  = document.getElementById('card-template-' + accomId);
        const container = document.getElementById('cards-container-' + accomId);
        const notice    = document.getElementById('empty-notice-' + accomId);
        if (!template || !container) return;

        const idx   = Date.now();
        const clone = template.content.cloneNode(true);

        clone.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace(/__IDX__/g, idx);
        });

        container.appendChild(clone);
        if (notice) notice.classList.add('hidden');
    };

    /* ── Remove unsaved new card ─────────────────────────────────────── */
    window.removeNewCard = function (btn, accomId) {
        btn.closest('.new-price-card').remove();
        checkEmpty(accomId);
    };

    /* ── Delete existing (saved) card via AJAX ───────────────────────── */
    window.deleteExistingCard = async function (priceId, cardId, accomId) {
        if (!confirm('Remove this date-range pricing? This cannot be undone.')) return;

        try {
            const res  = await fetch('{{ route("center-panel.availability.delete_price") }}', {
                method:  'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                },
                body: JSON.stringify({ id: priceId }),
            });
            const text = await res.text();
            if (text.trim() === '1' || text.trim() === 'true') {
                document.getElementById(cardId)?.remove();
                checkEmpty(accomId);
            } else {
                alert('Could not delete this range. Please try again.');
            }
        } catch (e) {
            alert('Network error. Please check your connection and try again.');
        }
    };

    /* ── Show/hide empty notice ──────────────────────────────────────── */
    window.checkEmpty = function (accomId) {
        const container = document.getElementById('cards-container-' + accomId);
        const notice    = document.getElementById('empty-notice-' + accomId);
        if (!container || !notice) return;
        const hasCards = container.querySelectorAll('.price-card, .new-price-card').length > 0;
        notice.classList.toggle('hidden', hasCards);
    };

})();
</script>
@endsection
