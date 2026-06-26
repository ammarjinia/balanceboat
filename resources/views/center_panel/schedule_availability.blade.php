@extends('layouts.center')

@section('title', 'Schedule Availability — ' . $experience->name)

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

    .fi {
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

    .field-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748B;
        display: block;
        margin-bottom: 4px;
        font-family: 'Outfit', sans-serif;
    }

    /* Status badge styles */
    .badge-open     { background: #E5F0E8; color: #2F6F57; }
    .badge-few_left { background: #FDF5E0; color: #B8960E; }
    .badge-full     { background: #FDE8E6; color: #C9504A; }
    .badge-closed   { background: #F1F5F9; color: #64748B; }

    /* Status select option colors */
    .status-open     { color: #2F6F57; }
    .status-few_left { color: #B8960E; }
    .status-full     { color: #C9504A; }
    .status-closed   { color: #64748B; }

    /* Row hover */
    .avail-row { transition: background 0.15s; }
    .avail-row:hover td { background: rgba(47,111,87,0.03); }

    /* Matrix cell */
    .matrix-cell {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 60px;
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 700;
    }

    /* Saving animation */
    @keyframes savePulse { 0%,100% { opacity: 1; } 50% { opacity: 0.5; } }
    .saving { animation: savePulse 0.8s infinite; }

    [x-cloak] { display: none !important; }
</style>
@endsection

@section('content')

@php
    $totalDates     = $availabilityRows->count();
    $openCount      = $availabilityRows->where('status', 'open')->count();
    $fewLeftCount   = $availabilityRows->where('status', 'few_left')->count();
    $fullCount      = $availabilityRows->where('status', 'full')->count();
    $closedCount    = $availabilityRows->where('status', 'closed')->count();
    $selectedAccom  = $accommodations->firstWhere('id', $selectedAccomId);
@endphp

{{-- ── Breadcrumb + Header ─────────────────────────────────────────────── --}}
<div class="border-b border-[#2F6F57]/10 pb-6 space-y-3 bb-sans">
    <nav class="flex items-center gap-2 text-xs text-slate-400 flex-wrap">
        <a href="{{ route('center-panel.availability') }}" class="hover:text-[#2F6F57] transition-colors">Availability & Pricing</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <a href="{{ route('center-panel.availability.manage', $experience->id) }}" class="hover:text-[#2F6F57] transition-colors">{{ $experience->name }}</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <span class="text-[#1E2522] font-semibold">Schedule Availability</span>
    </nav>
    <div class="flex flex-col md:flex-row md:items-end gap-4 justify-between">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-[#2F6F57] mb-1">Start Date Inventory</p>
            <h1 class="bb-serif text-3xl font-medium text-[#1E2522]">Schedule Availability</h1>
            <p class="text-[#64748B] text-sm mt-1 font-light">
                Manage room inventory and availability status per experience start date.
            </p>
        </div>
        <a href="{{ route('center-panel.availability.manage', $experience->id) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#2F6F57]/20 text-[#64748B] text-xs font-semibold hover:bg-slate-50 transition-all shrink-0">
            <i class="fa-solid fa-sliders text-[10px]"></i> Edit Pricing
        </a>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-emerald-700 bb-sans">
        <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="bg-rose-50 border border-rose-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-rose-700 bb-sans">
        <i class="fa-solid fa-circle-exclamation text-rose-500"></i> {{ session('error') }}
    </div>
@endif

{{-- ── Stats Row ────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 bb-sans">
    <div class="glass-forest p-4 rounded-2xl flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-[#2F6F57]/8 flex items-center justify-center shrink-0">
            <i class="fa-regular fa-calendar-days text-[#2F6F57] text-sm"></i>
        </div>
        <div>
            <p class="bb-serif text-xl font-semibold text-[#1E2522] leading-none">{{ $totalDates }}</p>
            <p class="text-[10px] text-[#64748B] mt-0.5">Start Dates</p>
        </div>
    </div>
    <div class="glass-forest p-4 rounded-2xl flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-[#2F6F57]/8 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-circle-check text-[#2F6F57] text-sm"></i>
        </div>
        <div>
            <p class="bb-serif text-xl font-semibold text-[#2F6F57] leading-none">{{ $openCount }}</p>
            <p class="text-[10px] text-[#64748B] mt-0.5">Open</p>
        </div>
    </div>
    <div class="glass-forest p-4 rounded-2xl flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-circle-exclamation text-amber-500 text-sm"></i>
        </div>
        <div>
            <p class="bb-serif text-xl font-semibold text-amber-600 leading-none">{{ $fewLeftCount }}</p>
            <p class="text-[10px] text-[#64748B] mt-0.5">Few Left</p>
        </div>
    </div>
    <div class="glass-forest p-4 rounded-2xl flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-circle-xmark text-rose-500 text-sm"></i>
        </div>
        <div>
            <p class="bb-serif text-xl font-semibold text-rose-600 leading-none">{{ $fullCount + $closedCount }}</p>
            <p class="text-[10px] text-[#64748B] mt-0.5">Full / Closed</p>
        </div>
    </div>
</div>

{{-- ── Accommodation Tabs ───────────────────────────────────────────────── --}}
<div class="bb-sans">
    <p class="field-label mb-2">Select Accommodation</p>
    <div class="flex flex-wrap gap-2">
        @foreach($accommodations as $accom)
        <a href="{{ route('center-panel.availability.schedule', [$experience->id, 'accom' => $accom->id]) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold border transition-all
               {{ $accom->id == $selectedAccomId
                   ? 'border-[#2F6F57] bg-[#2F6F57]/8 text-[#2F6F57]'
                   : 'border-[#2F6F57]/15 bg-white text-[#64748B] hover:border-[#2F6F57]/30 hover:text-[#1E2522]' }}">
            <span>{{ $accom->name }}</span>
            @if($accom->max_guest_in_room)
            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-md
                {{ $accom->id == $selectedAccomId ? 'bg-[#2F6F57]/15 text-[#2F6F57]' : 'bg-slate-100 text-slate-500' }}">
                {{ $accom->max_guest_in_room }} cap
            </span>
            @endif
        </a>
        @endforeach
    </div>
</div>

{{-- ── Main Panel: Availability Table ─────────────────────────────────── --}}
<div x-data="scheduleApp()" x-init="init()" class="space-y-4 bb-sans">

    {{-- Add Start Date Form --}}
    <div class="glass-forest rounded-3xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4">
            <div>
                <h3 class="text-sm font-bold text-[#1E2522]">
                    {{ $selectedAccom?->name ?? 'Accommodation' }} — Start Dates
                </h3>
                <p class="text-[11px] text-[#64748B] font-light mt-0.5">
                    {{ $totalDates }} date{{ $totalDates != 1 ? 's' : '' }} configured for this accommodation
                </p>
            </div>
            <button @click="showAddForm = !showAddForm"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all
                           bg-[#2F6F57] text-white hover:bg-[#255a46] active:scale-95">
                <i class="fa-solid" :class="showAddForm ? 'fa-minus' : 'fa-plus'" style="font-size:10px;"></i>
                <span x-text="showAddForm ? 'Cancel' : 'Add Start Date'"></span>
            </button>
        </div>

        {{-- Inline add form --}}
        <div x-show="showAddForm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="border-t border-[#2F6F57]/10 px-5 pb-5 pt-4"
             x-cloak>
            <p class="text-[10px] font-bold uppercase tracking-wide text-[#64748B] mb-3">New Start Date</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3 items-end">
                <div class="col-span-2 sm:col-span-1 lg:col-span-1">
                    <span class="field-label">Start Date <span class="text-rose-500">*</span></span>
                    <input type="date" x-model="newRow.start_date" class="fi" :min="today">
                </div>
                <div>
                    <span class="field-label">Status</span>
                    <select x-model="newRow.status" class="fi">
                        <option value="open">Open</option>
                        <option value="few_left">Few Left</option>
                        <option value="full">Full</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div>
                    <span class="field-label">Total Rooms</span>
                    <input type="number" x-model.number="newRow.total_rooms" class="fi" min="0" placeholder="0" @input="autoStatus()">
                </div>
                <div>
                    <span class="field-label">Booked</span>
                    <input type="number" x-model.number="newRow.booked_rooms" class="fi" min="0" placeholder="0" @input="autoStatus()">
                </div>
                <div>
                    <span class="field-label">Remaining</span>
                    <div class="fi bg-slate-50 text-[#64748B] cursor-default" x-text="Math.max(0, newRow.total_rooms - newRow.booked_rooms)"></div>
                </div>
                <div class="flex items-end">
                    <button @click="addRow()"
                            :disabled="!newRow.start_date || saving"
                            class="w-full py-2 rounded-xl text-xs font-bold transition-all bg-[#2F6F57] text-white hover:bg-[#255a46] active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed">
                        <span x-show="!saving">Add Date</span>
                        <span x-show="saving" class="saving">Saving…</span>
                    </button>
                </div>
            </div>
            <div x-show="addError" x-text="addError" class="mt-2 text-xs text-rose-600 font-medium" x-cloak></div>
        </div>
    </div>

    {{-- Availability Table --}}
    <div class="glass-forest rounded-3xl overflow-hidden">

        @if($availabilityRows->isEmpty())
        <div class="py-16 flex flex-col items-center text-center px-6">
            <div class="w-16 h-16 rounded-3xl bg-[#2F6F57]/5 border border-[#2F6F57]/15 flex items-center justify-center mb-4">
                <i class="fa-regular fa-calendar-plus text-2xl text-[#2F6F57]/30"></i>
            </div>
            <h3 class="bb-serif text-lg font-medium text-[#1E2522] mb-1">No start dates yet</h3>
            <p class="text-xs text-[#64748B] max-w-xs font-light leading-relaxed">
                Add start dates above to configure inventory and availability status for <strong>{{ $selectedAccom?->name }}</strong>.
            </p>
        </div>
        @else

        {{-- Bulk status bar --}}
        <div class="flex items-center justify-between gap-4 px-5 py-3 border-b border-[#2F6F57]/8 bg-[#2F6F57]/2">
            <p class="text-[11px] text-[#64748B] font-light">
                Edit any row inline — changes save individually with the row's Save button.
            </p>
            <div x-show="pendingChanges > 0" class="text-[11px] text-amber-600 font-semibold" x-cloak>
                <i class="fa-solid fa-circle-dot text-[8px] mr-1"></i>
                <span x-text="pendingChanges + ' unsaved change' + (pendingChanges > 1 ? 's' : '')"></span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs bb-sans">
                <thead>
                    <tr class="border-b border-[#2F6F57]/8">
                        <th class="text-left px-5 py-3 font-bold text-[10px] uppercase tracking-wider text-[#64748B]">Start Date</th>
                        <th class="text-left px-4 py-3 font-bold text-[10px] uppercase tracking-wider text-[#64748B]">Status</th>
                        <th class="text-center px-4 py-3 font-bold text-[10px] uppercase tracking-wider text-[#64748B]">Total Rooms</th>
                        <th class="text-center px-4 py-3 font-bold text-[10px] uppercase tracking-wider text-[#64748B]">Booked</th>
                        <th class="text-center px-4 py-3 font-bold text-[10px] uppercase tracking-wider text-[#64748B]">Remaining</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody id="availability-table-body">
                    @foreach($availabilityRows as $row)
                    @php $remaining = max(0, $row->total_rooms - $row->booked_rooms); @endphp
                    <tr class="avail-row border-b border-[#2F6F57]/5 last:border-0"
                        id="row-{{ $row->id }}"
                        x-data="rowApp({{ $row->id }}, '{{ $row->start_date->toDateString() }}', '{{ $row->status }}', {{ $row->total_rooms }}, {{ $row->booked_rooms }})">

                        {{-- Start Date --}}
                        <td class="px-5 py-3">
                            <div class="font-semibold text-[#1E2522]">
                                {{ $row->start_date->format('D, d M Y') }}
                            </div>
                            <div class="text-[10px] text-[#64748B] mt-0.5">
                                {{ $row->start_date->diffForHumans() }}
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3">
                            <select x-model="status"
                                    @change="markDirty()"
                                    class="fi w-auto min-w-[110px] text-xs font-semibold"
                                    :class="{
                                        'text-[#2F6F57] border-[#2F6F57]/30 bg-[#E5F0E8]': status === 'open',
                                        'text-amber-700 border-amber-200 bg-amber-50': status === 'few_left',
                                        'text-rose-600 border-rose-200 bg-rose-50': status === 'full',
                                        'text-slate-500 border-slate-200 bg-slate-50': status === 'closed'
                                    }">
                                <option value="open">Open</option>
                                <option value="few_left">Few Left</option>
                                <option value="full">Full</option>
                                <option value="closed">Closed</option>
                            </select>
                        </td>

                        {{-- Total Rooms --}}
                        <td class="px-4 py-3">
                            <input type="number"
                                   x-model.number="totalRooms"
                                   @input="recalc(); markDirty();"
                                   min="0"
                                   class="fi w-20 text-center"
                                   placeholder="0">
                        </td>

                        {{-- Booked --}}
                        <td class="px-4 py-3">
                            <input type="number"
                                   x-model.number="bookedRooms"
                                   @input="recalc(); markDirty();"
                                   :max="totalRooms"
                                   min="0"
                                   class="fi w-20 text-center"
                                   placeholder="0">
                        </td>

                        {{-- Remaining --}}
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded-xl text-xs font-bold"
                                  :class="{
                                      'bg-[#E5F0E8] text-[#2F6F57]': remaining > 0,
                                      'bg-rose-50 text-rose-600': remaining === 0
                                  }"
                                  x-text="remaining"></span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2 justify-end">
                                <button @click="saveRow()"
                                        :disabled="saving"
                                        :class="{ 'opacity-60 cursor-not-allowed': saving }"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold transition-all
                                               bg-[#2F6F57]/10 text-[#2F6F57] hover:bg-[#2F6F57]/20 active:scale-95">
                                    <i class="fa-solid fa-floppy-disk text-[9px]"></i>
                                    <span x-text="saving ? 'Saving…' : 'Save'"></span>
                                </button>
                                <button @click="deleteRow()"
                                        :disabled="saving"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg text-rose-400 hover:bg-rose-50 hover:text-rose-600 transition-all">
                                    <i class="fa-regular fa-trash-can text-[10px]"></i>
                                </button>
                            </div>
                            <div x-show="saved" x-cloak
                                 class="text-[10px] text-[#2F6F57] font-semibold mt-1 text-right">
                                ✓ Saved
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Bulk Save via POST as fallback --}}
        <div class="px-5 py-4 border-t border-[#2F6F57]/8 flex items-center justify-between gap-4">
            <p class="text-[11px] text-[#64748B] font-light">
                <i class="fa-solid fa-circle-info text-[#2F6F57]/40 mr-1"></i>
                Use individual row Save buttons, or click Save All to commit all changes at once.
            </p>
            <form method="POST" action="{{ route('center-panel.availability.schedule.save', $experience->id) }}" id="bulk-save-form">
                @csrf
                <input type="hidden" name="accommodation_id" value="{{ $selectedAccomId }}">
                @foreach($availabilityRows as $row)
                <input type="hidden" name="rows[{{ $loop->index }}][start_date]" value="{{ $row->start_date->toDateString() }}" data-row-field="{{ $row->id }}-date">
                <input type="hidden" name="rows[{{ $loop->index }}][status]"     id="hidden-status-{{ $row->id }}"  value="{{ $row->status }}">
                <input type="hidden" name="rows[{{ $loop->index }}][total_rooms]" id="hidden-total-{{ $row->id }}"  value="{{ $row->total_rooms }}">
                <input type="hidden" name="rows[{{ $loop->index }}][booked_rooms]" id="hidden-booked-{{ $row->id }}" value="{{ $row->booked_rooms }}">
                @endforeach
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#2F6F57] text-white text-xs font-bold hover:bg-[#255a46] active:scale-95 transition-all shadow-sm shadow-[#2F6F57]/20">
                    <i class="fa-solid fa-floppy-disk text-[11px]"></i> Save All Changes
                </button>
            </form>
        </div>

        @endif
    </div>
</div>

{{-- ── Overview Matrix ─────────────────────────────────────────────────── --}}
@if($uniqueDates->isNotEmpty())
<div class="glass-forest rounded-3xl overflow-hidden bb-sans">
    <div class="px-5 py-4 border-b border-[#2F6F57]/8">
        <h3 class="text-sm font-bold text-[#1E2522]">Availability Overview</h3>
        <p class="text-[11px] text-[#64748B] font-light mt-0.5">All accommodations × all configured start dates</p>
    </div>
    <div class="overflow-x-auto p-5">
        <table class="w-full text-[11px]">
            <thead>
                <tr>
                    <th class="text-left pb-3 pr-4 font-bold text-[10px] uppercase tracking-wider text-[#64748B] min-w-[130px]">Accommodation</th>
                    @foreach($uniqueDates as $date)
                    <th class="text-center pb-3 px-2 font-bold text-[10px] uppercase tracking-wider text-[#64748B] min-w-[80px]">
                        {{ \Carbon\Carbon::parse($date)->format('d M') }}
                        <div class="text-[9px] font-normal normal-case tracking-normal text-slate-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($date)->format('D') }}
                        </div>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($accommodations as $accom)
                <tr class="border-t border-[#2F6F57]/5">
                    <td class="py-2.5 pr-4">
                        <span class="font-semibold text-[#1E2522]">{{ $accom->name }}</span>
                        @if($accom->max_guest_in_room)
                        <span class="text-[9px] text-slate-400 ml-1">{{ $accom->max_guest_in_room }} cap</span>
                        @endif
                    </td>
                    @foreach($uniqueDates as $date)
                    @php $cell = $matrix[$accom->id][$date->toDateString()] ?? null; @endphp
                    <td class="py-2.5 px-2 text-center">
                        @if($cell)
                            @php
                                $remaining = max(0, $cell->total_rooms - $cell->booked_rooms);
                                $badgeClass = match($cell->status) {
                                    'open'     => 'bg-[#E5F0E8] text-[#2F6F57]',
                                    'few_left' => 'bg-[#FDF5E0] text-amber-700',
                                    'full'     => 'bg-[#FDE8E6] text-rose-600',
                                    'closed'   => 'bg-slate-100 text-slate-500',
                                    default    => 'bg-slate-100 text-slate-400',
                                };
                                $label = match($cell->status) {
                                    'open'     => $remaining . ' left',
                                    'few_left' => $remaining . ' left',
                                    'full'     => 'Full',
                                    'closed'   => 'Closed',
                                    default    => '—',
                                };
                            @endphp
                            <span class="matrix-cell {{ $badgeClass }}">{{ $label }}</span>
                        @else
                            <span class="text-slate-300">—</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const UPDATE_URL = '{{ route("center-panel.availability.schedule.update") }}';
const DELETE_URL = '{{ route("center-panel.availability.schedule.delete") }}';
const EXPERIENCE_ID = {{ $experience->id }};
const ACCOM_ID      = {{ $selectedAccomId }};

/* ── Page-level Alpine component ─────────────────────────────────────── */
function scheduleApp() {
    return {
        showAddForm: false,
        saving: false,
        addError: '',
        pendingChanges: 0,
        today: new Date().toISOString().split('T')[0],
        newRow: { start_date: '', status: 'open', total_rooms: 0, booked_rooms: 0 },

        init() {},

        autoStatus() {
            const r = this.newRow;
            const remaining = Math.max(0, r.total_rooms - r.booked_rooms);
            if (r.total_rooms > 0) {
                if (remaining === 0) r.status = 'full';
                else if (remaining / r.total_rooms <= 0.20) r.status = 'few_left';
                else r.status = 'open';
            }
        },

        async addRow() {
            if (!this.newRow.start_date) { this.addError = 'Please select a start date.'; return; }
            this.addError = '';
            this.saving = true;
            try {
                const res = await fetch(UPDATE_URL, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        experience_id:    EXPERIENCE_ID,
                        accommodation_id: ACCOM_ID,
                        start_date:       this.newRow.start_date,
                        status:           this.newRow.status,
                        total_rooms:      this.newRow.total_rooms,
                        booked_rooms:     this.newRow.booked_rooms,
                    })
                });
                if (res.ok) {
                    // Reload to show the new row with proper date formatting
                    window.location.reload();
                } else {
                    const json = await res.json();
                    this.addError = json.error ?? 'Failed to add start date.';
                }
            } catch {
                this.addError = 'Network error. Please try again.';
            } finally {
                this.saving = false;
            }
        }
    };
}

/* ── Per-row Alpine component ─────────────────────────────────────────── */
function rowApp(id, startDate, initialStatus, initialTotal, initialBooked) {
    return {
        id, startDate,
        status:      initialStatus,
        totalRooms:  initialTotal,
        bookedRooms: initialBooked,
        remaining:   Math.max(0, initialTotal - initialBooked),
        saving:      false,
        saved:       false,
        dirty:       false,

        init() {},

        recalc() {
            this.remaining = Math.max(0, this.totalRooms - this.bookedRooms);
        },

        markDirty() {
            if (!this.dirty) {
                this.dirty = true;
                // Sync to hidden bulk-save inputs
                const statusEl  = document.getElementById('hidden-status-'  + this.id);
                const totalEl   = document.getElementById('hidden-total-'   + this.id);
                const bookedEl  = document.getElementById('hidden-booked-'  + this.id);
                if (statusEl)  statusEl.value  = this.status;
                if (totalEl)   totalEl.value   = this.totalRooms;
                if (bookedEl)  bookedEl.value  = this.bookedRooms;
            }
        },

        async saveRow() {
            this.saving = true;
            this.saved  = false;
            // Sync hidden inputs
            const statusEl  = document.getElementById('hidden-status-'  + this.id);
            const totalEl   = document.getElementById('hidden-total-'   + this.id);
            const bookedEl  = document.getElementById('hidden-booked-'  + this.id);
            if (statusEl)  statusEl.value  = this.status;
            if (totalEl)   totalEl.value   = this.totalRooms;
            if (bookedEl)  bookedEl.value  = this.bookedRooms;

            try {
                const res = await fetch(UPDATE_URL, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        experience_id:    EXPERIENCE_ID,
                        accommodation_id: ACCOM_ID,
                        start_date:       this.startDate,
                        status:           this.status,
                        total_rooms:      this.totalRooms,
                        booked_rooms:     this.bookedRooms,
                    })
                });
                if (res.ok) {
                    this.dirty = false;
                    this.saved = true;
                    setTimeout(() => { this.saved = false; }, 3000);
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.saving = false;
            }
        },

        async deleteRow() {
            if (!confirm('Remove this start date? This cannot be undone.')) return;
            this.saving = true;
            try {
                const res = await fetch(DELETE_URL, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ id: this.id })
                });
                if (res.ok) {
                    const el = document.getElementById('row-' + this.id);
                    if (el) {
                        el.style.transition = 'opacity 0.3s';
                        el.style.opacity    = '0';
                        setTimeout(() => el.remove(), 300);
                    }
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.saving = false;
            }
        }
    };
}
</script>
@endsection
