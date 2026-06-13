@extends('layouts.center')

@section('title', 'Availability & Pricing — ' . $experience->name)

@section('head')
<style>
    /* Tighten table inputs */
    #availability-form input[type="date"],
    #availability-form input[type="number"],
    #availability-form input[type="text"] {
        min-width: 0;
    }
    /* Smooth accordion */
    [x-cloak] { display: none !important; }
</style>
@endsection

@section('content')

    {{-- Breadcrumb + Header --}}
    <div class="border-b border-slate-200 pb-6 space-y-3">
        <nav class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('center-panel.availability') }}" class="hover:text-purple-600 transition-colors">Availability & Pricing</a>
            <i class="fa-solid fa-chevron-right text-[9px]"></i>
            <span class="text-slate-700 font-semibold">{{ $experience->name }}</span>
        </nav>
        <div class="flex items-start gap-4">
            <div class="flex-1 min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-widest text-purple-500 mb-1">Experience Pricing</p>
                <h1 class="text-3xl font-serif font-bold text-slate-900 leading-tight">{{ $experience->name }}</h1>
                <p class="text-slate-500 text-sm mt-1">
                    Configure which accommodation types are included, set base pricing, and define date-range overrides.
                </p>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-emerald-700">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-rose-50 border border-rose-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-rose-700">
            <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($centerAccommodations->isEmpty())

        {{-- No Accommodations Linked --}}
        <div class="glass rounded-3xl shadow-sm">
            <div class="py-16 flex flex-col items-center text-center px-6">
                <div class="w-16 h-16 rounded-3xl bg-amber-50 border border-amber-100 flex items-center justify-center mb-4 shadow-sm">
                    <i class="fa-regular fa-bed text-2xl text-amber-400"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800 mb-1">No accommodations linked</h3>
                <p class="text-xs text-slate-500 max-w-xs mb-5 leading-relaxed">
                    You need to add accommodations to your center first before you can configure pricing for this experience.
                </p>
                <a href="{{ route('center-panel.accommodation.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 text-white rounded-2xl text-xs font-semibold hover:bg-purple-700 transition-all shadow-sm shadow-purple-200">
                    <i class="fa-solid fa-plus"></i> Add Accommodation
                </a>
            </div>
        </div>

    @else

        <form id="availability-form"
              method="POST"
              action="{{ route('center-panel.availability.save', $experience->id) }}">
            @csrf

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

                {{-- ── Left Column: Accordion Cards ── --}}
                <div class="xl:col-span-8 space-y-4">

                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-600 uppercase tracking-wider">
                            Accommodation Options
                            <span class="ml-2 font-normal text-slate-400 normal-case tracking-normal">
                                {{ $centerAccommodations->count() }} available at your center
                            </span>
                        </p>
                        <p class="text-[11px] text-slate-400">Toggle to include in this experience</p>
                    </div>

                    @foreach($centerAccommodations as $accom)
                    @php
                        $ea         = $existingEA[$accom->id] ?? null;
                        $isIncluded = !is_null($ea);
                        $isDefault  = $ea && $ea->accomodation_default == 1;
                        $prices     = $existingPrices[$accom->id] ?? collect();
                    @endphp

                    <div x-data="{
                            open: {{ $isIncluded ? 'true' : 'false' }},
                            included: {{ $isIncluded ? 'true' : 'false' }}
                         }"
                         class="glass rounded-3xl shadow-sm overflow-hidden transition-all"
                         :class="{ 'ring-2 ring-purple-200': included }">

                        {{-- Card Header --}}
                        <div class="flex items-center gap-4 px-5 py-4 cursor-pointer select-none"
                             @click="if (included) { open = !open }">

                            {{-- Include Toggle --}}
                            <label class="relative inline-flex items-center cursor-pointer shrink-0" @click.stop>
                                <input type="checkbox"
                                       x-model="included"
                                       name="accommodations[{{ $accom->id }}][included]"
                                       value="1"
                                       @change="if ($event.target.checked) { open = true }"
                                       class="sr-only peer">
                                <div class="relative w-10 h-6 rounded-full transition-colors duration-200
                                            bg-slate-200 peer-checked:bg-purple-600
                                            after:content-[''] after:absolute after:top-[3px] after:left-[3px]
                                            after:w-[18px] after:h-[18px] after:bg-white after:rounded-full
                                            after:shadow-sm after:transition-all after:duration-200
                                            peer-checked:after:translate-x-4">
                                </div>
                            </label>

                            {{-- Accommodation Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-semibold text-sm text-slate-900">{{ $accom->name }}</span>

                                    @if($accom->max_guest_in_room)
                                    <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-500 text-[10px] font-semibold px-2 py-0.5 rounded-lg shrink-0">
                                        <i class="fa-solid fa-user text-[8px]"></i>
                                        {{ $accom->max_guest_in_room }} {{ $accom->max_guest_in_room == 1 ? 'guest' : 'guests' }}
                                    </span>
                                    @endif

                                    @if($isDefault)
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-600 text-[10px] font-bold px-2 py-0.5 rounded-lg shrink-0">
                                        <i class="fa-solid fa-star text-[8px]"></i> Default
                                    </span>
                                    @endif

                                    @if($prices->count())
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[10px] font-semibold px-2 py-0.5 rounded-lg shrink-0">
                                        <i class="fa-regular fa-tag text-[8px]"></i>
                                        {{ $prices->count() }} {{ $prices->count() == 1 ? 'range' : 'ranges' }}
                                    </span>
                                    @endif
                                </div>

                                @if($ea && $ea->price_per_night_per_guest)
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    Base: <span class="font-semibold text-slate-600">{{ $ea->currency }} {{ number_format($ea->price_per_night_per_guest, 0) }}</span>/night · per guest
                                </p>
                                @else
                                <p class="text-[11px] text-slate-400 mt-0.5" x-show="!included">Toggle on to configure pricing</p>
                                @endif
                            </div>

                            {{-- Chevron --}}
                            <div class="shrink-0">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors"
                                     :class="included ? 'bg-slate-100' : 'bg-transparent'">
                                    <i class="fa-solid fa-chevron-down text-slate-400 text-[10px] transition-transform duration-200"
                                       :class="{ 'rotate-180': open && included }"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div x-show="open && included"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             class="border-t border-slate-100 px-5 pb-5 pt-4 space-y-5">

                            {{-- Hidden EA ID (for existing records) --}}
                            @if($ea)
                            <input type="hidden" name="accommodations[{{ $accom->id }}][ea_id]" value="{{ $ea->id }}">
                            @endif

                            {{-- Base Price Row --}}
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">

                                {{-- Base Price --}}
                                <div class="sm:col-span-5">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Base Price
                                        <span class="font-normal text-slate-400">per night · per guest</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-semibold pointer-events-none">
                                            {{ array_key_first($currencies) }}
                                        </span>
                                        <input type="number"
                                               name="accommodations[{{ $accom->id }}][base_price]"
                                               value="{{ $ea->price_per_night_per_guest ?? '' }}"
                                               min="0" step="0.01" placeholder="0.00"
                                               class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 bg-white/70 text-sm text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all">
                                    </div>
                                </div>

                                {{-- Currency --}}
                                <div class="sm:col-span-3">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Currency</label>
                                    <select name="accommodations[{{ $accom->id }}][currency]"
                                            class="w-full px-3.5 py-2.5 rounded-2xl border border-slate-200 bg-white/70 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all">
                                        @foreach($currencies as $code => $label)
                                            <option value="{{ $code }}" {{ ($ea->currency ?? 'USD') === $code ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Default Radio --}}
                                <div class="sm:col-span-4">
                                    <label class="flex items-center gap-2.5 cursor-pointer group h-[42px] px-3.5 rounded-2xl border border-slate-200 bg-white/70 hover:border-amber-300 hover:bg-amber-50/40 transition-all">
                                        <input type="radio"
                                               name="default_accommodation"
                                               value="{{ $accom->id }}"
                                               {{ $isDefault ? 'checked' : '' }}
                                               class="w-3.5 h-3.5 accent-amber-500">
                                        <span class="text-xs font-semibold text-slate-700 group-hover:text-amber-700 transition-colors leading-tight">
                                            Set as Default Room
                                        </span>
                                    </label>
                                </div>
                            </div>

                            {{-- Seasonal Pricing Section --}}
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-700">Seasonal Pricing</h4>
                                        <p class="text-[11px] text-slate-400 mt-0.5">Override base price for specific date ranges or retreat durations</p>
                                    </div>
                                    <button type="button"
                                            onclick="addPriceRow({{ $accom->id }})"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-purple-50 text-purple-700 rounded-xl text-xs font-semibold hover:bg-purple-100 active:scale-95 transition-all shrink-0">
                                        <i class="fa-solid fa-plus text-[10px]"></i>
                                        Add Range
                                    </button>
                                </div>

                                {{-- Empty Notice --}}
                                <div id="empty-notice-{{ $accom->id }}"
                                     class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 py-6 text-center {{ $prices->isEmpty() ? '' : 'hidden' }}">
                                    <i class="fa-regular fa-calendar-plus text-2xl text-slate-300 mb-2 block"></i>
                                    <p class="text-xs text-slate-400">No seasonal overrides yet.</p>
                                    <p class="text-[11px] text-slate-400">Click <strong class="text-purple-600 font-semibold">Add Range</strong> to define date-based rates.</p>
                                </div>

                                {{-- Ranges Table --}}
                                <div id="table-wrap-{{ $accom->id }}"
                                     class="rounded-2xl border border-slate-200 overflow-hidden {{ $prices->isEmpty() ? 'hidden' : '' }}">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-xs">
                                            <thead class="bg-slate-50/80 border-b border-slate-100">
                                                <tr>
                                                    <th class="text-left px-3.5 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">Start Date</th>
                                                    <th class="text-left px-3.5 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">End Date</th>
                                                    <th class="text-left px-3.5 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">Nights</th>
                                                    <th class="text-left px-3.5 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">Price / Night</th>
                                                    <th class="text-left px-3.5 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">Promo Price</th>
                                                    <th class="text-left px-3.5 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 whitespace-nowrap">Discount</th>
                                                    <th class="px-3.5 py-2.5 w-10"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="ranges-body-{{ $accom->id }}" class="divide-y divide-slate-100">

                                                @foreach($prices as $price)
                                                <tr class="hover:bg-purple-50/20 transition-colors" id="row-{{ $price->id }}">
                                                    <td class="px-3 py-2">
                                                        <input type="hidden" name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][price_id]" value="{{ $price->id }}">
                                                        <input type="date"
                                                               name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][start_date]"
                                                               value="{{ $price->start_date }}"
                                                               class="block w-full px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all" style="min-width:130px">
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <input type="date"
                                                               name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][end_date]"
                                                               value="{{ $price->end_date }}"
                                                               class="block w-full px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all" style="min-width:130px">
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <input type="number"
                                                               name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][duration]"
                                                               value="{{ $price->duration }}"
                                                               min="1" placeholder="—"
                                                               class="block w-full px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all" style="min-width:70px">
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <input type="number"
                                                               name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][price]"
                                                               value="{{ $price->price_per_night_per_guest }}"
                                                               min="0" step="0.01" placeholder="0.00"
                                                               class="block w-full px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all" style="min-width:90px">
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <input type="number"
                                                               name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][promo_price]"
                                                               value="{{ $price->promotional_price }}"
                                                               min="0" step="0.01" placeholder="—"
                                                               class="block w-full px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all" style="min-width:90px">
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <input type="text"
                                                               name="accommodations[{{ $accom->id }}][ranges][{{ $price->id }}][promo_discount]"
                                                               value="{{ $price->promotional_discount }}"
                                                               placeholder="—"
                                                               class="block w-full px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all" style="min-width:80px">
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <button type="button"
                                                                onclick="deletePriceRow({{ $price->id }}, this.closest('tr'), {{ $accom->id }})"
                                                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-50 text-rose-400 hover:bg-rose-100 hover:text-rose-600 transition-all mx-auto">
                                                            <i class="fa-regular fa-trash-can text-[11px]"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- New Row Template --}}
                                <template id="range-template-{{ $accom->id }}">
                                    <tr class="hover:bg-purple-50/20 transition-colors new-row">
                                        <td class="px-3 py-2">
                                            <input type="date"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][start_date]"
                                                   class="block w-full px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all" style="min-width:130px">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="date"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][end_date]"
                                                   class="block w-full px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all" style="min-width:130px">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][duration]"
                                                   min="1" placeholder="—"
                                                   class="block w-full px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all" style="min-width:70px">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][price]"
                                                   min="0" step="0.01" placeholder="0.00"
                                                   class="block w-full px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all" style="min-width:90px">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][promo_price]"
                                                   min="0" step="0.01" placeholder="—"
                                                   class="block w-full px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all" style="min-width:90px">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="text"
                                                   name="accommodations[{{ $accom->id }}][ranges][__IDX__][promo_discount]"
                                                   placeholder="—"
                                                   class="block w-full px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all" style="min-width:80px">
                                        </td>
                                        <td class="px-3 py-2">
                                            <button type="button"
                                                    onclick="removeNewRow(this, {{ $accom->id }})"
                                                    class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-50 text-rose-400 hover:bg-rose-100 hover:text-rose-600 transition-all mx-auto">
                                                <i class="fa-regular fa-trash-can text-[11px]"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>

                            </div>{{-- /seasonal pricing --}}

                        </div>{{-- /card body --}}

                    </div>{{-- /accordion card --}}
                    @endforeach

                </div>{{-- /left col --}}

                {{-- ── Right Column: Sticky Sidebar ── --}}
                <div class="xl:col-span-4">
                    <div class="sticky top-6 space-y-4">

                        {{-- Experience Summary --}}
                        <div class="glass rounded-3xl shadow-sm p-5 space-y-4">
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Experience Summary</h3>

                            <div class="flex items-start gap-3">
                                @if($experience->thumbnail_image_url)
                                <img src="{{ Storage::disk('azure')->url($experience->thumbnail_image_url) }}"
                                     alt="{{ $experience->name }}"
                                     class="w-14 h-14 rounded-2xl object-cover shrink-0">
                                @else
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-50 to-orange-50 border border-purple-100 flex items-center justify-center shrink-0">
                                    <i class="fa-regular fa-spa text-purple-300 text-xl"></i>
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 text-sm leading-tight">{{ $experience->name }}</p>
                                    @if($experience->experience_summary)
                                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                        {{ Str::limit(strip_tags($experience->experience_summary), 80) }}
                                    </p>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 pt-1 border-t border-slate-100">
                                @php
                                    $includedCount = $existingEA->count();
                                    $priceRangeCount = $existingPrices->flatten()->count();
                                @endphp
                                <div class="bg-purple-50/60 rounded-2xl p-3 text-center">
                                    <p class="text-xl font-bold text-purple-700">{{ $includedCount }}</p>
                                    <p class="text-[10px] text-purple-600 font-medium mt-0.5">Room Types</p>
                                </div>
                                <div class="bg-emerald-50/60 rounded-2xl p-3 text-center">
                                    <p class="text-xl font-bold text-emerald-700">{{ $priceRangeCount }}</p>
                                    <p class="text-[10px] text-emerald-600 font-medium mt-0.5">Price Ranges</p>
                                </div>
                            </div>
                        </div>

                        {{-- Save Card --}}
                        <div class="glass rounded-3xl shadow-sm p-5 space-y-3">
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Save Changes</h3>
                            <p class="text-[11px] text-slate-400 leading-relaxed">
                                Toggled-off accommodations will be removed from this experience along with their pricing data.
                            </p>
                            <button type="submit"
                                    class="w-full py-3 bg-purple-600 text-white rounded-2xl text-sm font-bold hover:bg-purple-700 active:scale-95 transition-all shadow-sm shadow-purple-200 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save Availability
                            </button>
                            <a href="{{ route('center-panel.availability') }}"
                               class="w-full py-2.5 border border-slate-200 text-slate-600 rounded-2xl text-xs font-semibold hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                                Back to Experiences
                            </a>
                        </div>

                        {{-- Tips Card --}}
                        <div class="rounded-3xl border border-blue-100 bg-blue-50/60 p-4 space-y-2">
                            <p class="text-xs font-bold text-blue-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-lightbulb text-blue-500"></i> Tips
                            </p>
                            <ul class="text-[11px] text-blue-600 space-y-1.5 leading-relaxed list-none">
                                <li class="flex items-start gap-1.5"><i class="fa-solid fa-circle-dot text-[7px] mt-[5px] shrink-0"></i><span>Set a <strong>Default Room</strong> to pre-select the most common option for guests.</span></li>
                                <li class="flex items-start gap-1.5"><i class="fa-solid fa-circle-dot text-[7px] mt-[5px] shrink-0"></i><span>Use <strong>Seasonal Pricing</strong> for peak-season or off-season overrides.</span></li>
                                <li class="flex items-start gap-1.5"><i class="fa-solid fa-circle-dot text-[7px] mt-[5px] shrink-0"></i><span><strong>Promo Price</strong> & Discount are optional — leave blank if not applicable.</span></li>
                                <li class="flex items-start gap-1.5"><i class="fa-solid fa-circle-dot text-[7px] mt-[5px] shrink-0"></i><span><strong>Nights</strong> links a price range to a specific retreat duration.</span></li>
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

    /* Add a new (unsaved) price row to an accommodation's table */
    window.addPriceRow = function (accomId) {
        const template = document.getElementById('range-template-' + accomId);
        const tbody    = document.getElementById('ranges-body-' + accomId);
        if (!template || !tbody) return;

        const idx   = Date.now();
        const clone = template.content.cloneNode(true);

        clone.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace(/__IDX__/g, idx);
        });

        tbody.appendChild(clone);
        showTable(accomId);
    };

    /* Remove a new (unsaved) row - no AJAX needed */
    window.removeNewRow = function (btn, accomId) {
        btn.closest('tr').remove();
        checkTableEmpty(accomId);
    };

    /* Delete an existing (saved) price row via AJAX */
    window.deletePriceRow = async function (priceId, rowEl, accomId) {
        if (!confirm('Remove this price range?')) return;

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
                rowEl.remove();
                checkTableEmpty(accomId);
            } else {
                alert('Could not delete this range. Please try again.');
            }
        } catch (e) {
            alert('Network error. Please check your connection and try again.');
        }
    };

    /* Show/hide the table vs empty notice based on row count */
    window.checkTableEmpty = function (accomId) {
        const tbody  = document.getElementById('ranges-body-' + accomId);
        const wrap   = document.getElementById('table-wrap-' + accomId);
        const notice = document.getElementById('empty-notice-' + accomId);
        if (!tbody) return;

        const hasRows = tbody.querySelectorAll('tr').length > 0;
        if (wrap)   wrap.classList.toggle('hidden', !hasRows);
        if (notice) notice.classList.toggle('hidden', hasRows);
    };

    window.showTable = function (accomId) {
        const wrap   = document.getElementById('table-wrap-' + accomId);
        const notice = document.getElementById('empty-notice-' + accomId);
        if (wrap)   wrap.classList.remove('hidden');
        if (notice) notice.classList.add('hidden');
    };
})();
</script>
@endsection
