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
        padding: 7px 10px;
        font-size: 12px;
        width: 100%;
        font-family: 'Outfit', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s;
        color: #1E2522;
    }
    .fi:focus {
        outline: none;
        border-color: #2F6F57;
        box-shadow: 0 0 0 3px rgba(47,111,87,0.10);
    }

    /* Calendar cell states */
    .cal-day {
        min-height: 64px;
        position: relative;
        cursor: pointer;
        transition: all 0.15s ease;
        border-radius: 12px;
        user-select: none;
    }
    .cal-day:hover { transform: scale(1.03); }
    .cal-day.empty { cursor: default; background: transparent !important; }
    .cal-day.empty:hover { transform: none; }

    .cal-day.unset {
        background: #fff;
        border: 1.5px dashed rgba(47,111,87,0.18);
    }
    .cal-day.unset:hover { border-color: #2F6F57; background: rgba(47,111,87,0.04); }

    .cal-day.status-open    { background: rgba(34,197,94,0.12);  border: 1.5px solid rgba(34,197,94,0.30); }
    .cal-day.status-few_left{ background: rgba(245,158,11,0.12); border: 1.5px solid rgba(245,158,11,0.35); }
    .cal-day.status-full    { background: rgba(239,68,68,0.10);  border: 1.5px solid rgba(239,68,68,0.28); }
    .cal-day.status-closed  { background: rgba(100,116,139,0.10); border: 1.5px solid rgba(100,116,139,0.25); }

    .cal-day.selected {
        box-shadow: 0 0 0 2.5px #2F6F57 !important;
        transform: scale(1.04);
    }
    .cal-day.in-range {
        box-shadow: 0 0 0 2px rgba(47,111,87,0.45);
        background: rgba(47,111,87,0.08) !important;
    }
    .cal-day.range-start, .cal-day.range-end {
        box-shadow: 0 0 0 2.5px #D4AF37 !important;
    }

    /* Status dot */
    .status-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        display: inline-block;
    }
    .dot-open     { background: #22c55e; }
    .dot-few_left { background: #f59e0b; }
    .dot-full     { background: #ef4444; }
    .dot-closed   { background: #94a3b8; }

    /* Overview matrix */
    .matrix-cell {
        padding: 4px 6px;
        border-radius: 6px;
        font-size: 10px;
        text-align: center;
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
    }
    .matrix-open     { background: rgba(34,197,94,0.15);  color: #15803d; }
    .matrix-few_left { background: rgba(245,158,11,0.15); color: #b45309; }
    .matrix-full     { background: rgba(239,68,68,0.12);  color: #dc2626; }
    .matrix-closed   { background: rgba(100,116,139,0.12); color: #64748b; }
    .matrix-none     { background: transparent; color: #cbd5e1; }
</style>
@endsection

@section('content')

<div class="bb-sans space-y-6"
     x-data="calendarApp()"
     x-init="init()">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('center-panel.availability.manage', $experience->id) }}"
                   class="text-[11px] text-[#2F6F57] hover:underline flex items-center gap-1">
                    <i class="fa-solid fa-chevron-left text-[9px]"></i> Back to Pricing
                </a>
            </div>
            <h1 class="bb-serif text-2xl font-semibold text-[#1E2522]">Schedule Availability</h1>
            <p class="text-sm text-[#64748B] mt-0.5 font-light">{{ $experience->name }}</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            {{-- Accommodation tabs --}}
            @foreach($accommodations as $accom)
            <a href="{{ route('center-panel.availability.schedule', [$experience->id, 'accom' => $accom->id]) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all
                      {{ $selectedAccomId == $accom->id
                          ? 'bg-[#2F6F57] text-white shadow-sm shadow-[#2F6F57]/25'
                          : 'bg-white border border-[#2F6F57]/20 text-[#2F6F57] hover:bg-[#2F6F57]/6' }}">
                <i class="fa-regular fa-bed text-[10px]"></i>
                {{ $accom->name }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-emerald-700">
        <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-rose-700">
        <i class="fa-solid fa-circle-exclamation text-rose-500"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        {{-- ── Calendar column ─────────────────────────────────────────── --}}
        <div class="xl:col-span-8 space-y-4">

            {{-- Calendar Card --}}
            <div class="glass-forest rounded-3xl p-5 space-y-4">

                {{-- Month nav --}}
                <div class="flex items-center justify-between">
                    <button type="button" @click="prevMonth()"
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#2F6F57]/8 text-[#2F6F57] hover:bg-[#2F6F57]/16 transition-all">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <div class="text-center">
                        <p class="bb-serif text-lg font-semibold text-[#1E2522]" x-text="monthLabel"></p>
                        <p class="text-[10px] text-[#64748B] font-light" x-text="selectionSummary()"></p>
                    </div>
                    <button type="button" @click="nextMonth()"
                            class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#2F6F57]/8 text-[#2F6F57] hover:bg-[#2F6F57]/16 transition-all">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>

                {{-- Mode toggle --}}
                <div class="flex items-center gap-2">
                    <button type="button" @click="mode = 'single'; clearSelection()"
                            :class="mode === 'single'
                                ? 'bg-[#2F6F57] text-white shadow-sm'
                                : 'bg-[#2F6F57]/8 text-[#2F6F57] hover:bg-[#2F6F57]/14'"
                            class="px-3 py-1.5 rounded-xl text-[11px] font-semibold transition-all flex items-center gap-1.5">
                        <i class="fa-regular fa-hand-pointer text-[10px]"></i> Single
                    </button>
                    <button type="button" @click="mode = 'range'; clearSelection()"
                            :class="mode === 'range'
                                ? 'bg-[#D4AF37] text-white shadow-sm'
                                : 'bg-amber-50 text-amber-700 hover:bg-amber-100'"
                            class="px-3 py-1.5 rounded-xl text-[11px] font-semibold transition-all flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar-range text-[10px]"></i> Date Range
                    </button>
                    <button type="button" @click="clearSelection()"
                            x-show="selectedDates.length > 0"
                            class="ml-auto px-3 py-1.5 rounded-xl text-[11px] font-semibold bg-slate-100 text-slate-500 hover:bg-slate-200 transition-all">
                        <i class="fa-solid fa-xmark text-[10px]"></i> Clear
                    </button>
                </div>

                {{-- Day headers --}}
                <div class="grid grid-cols-7 gap-1.5">
                    <template x-for="d in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']">
                        <div class="text-center text-[9px] font-bold uppercase tracking-wider text-[#64748B] py-1" x-text="d"></div>
                    </template>
                </div>

                {{-- Calendar grid --}}
                <div class="grid grid-cols-7 gap-1.5" @mouseleave="hoverDate = null">
                    <template x-for="cell in calendarCells" :key="cell.key">
                        <div
                            :class="cellClass(cell)"
                            class="cal-day p-2 flex flex-col"
                            @click="cell.date ? clickDay(cell.date) : null"
                            @mousemove="cell.date && mode === 'range' ? hoverDate = cell.date : null"
                        >
                            <template x-if="cell.date">
                                <div class="flex flex-col h-full">
                                    <span class="text-[11px] font-bold leading-none"
                                          :class="isToday(cell.date) ? 'text-[#2F6F57]' : 'text-[#1E2522]'"
                                          x-text="cell.day"></span>
                                    <template x-if="getData(cell.date)">
                                        <div class="mt-auto space-y-0.5">
                                            <div class="flex items-center gap-1">
                                                <span class="status-dot" :class="'dot-' + getData(cell.date).status"></span>
                                                <span class="text-[9px] font-semibold leading-none capitalize"
                                                      :class="{
                                                          'text-emerald-700': getData(cell.date).status === 'open',
                                                          'text-amber-700':   getData(cell.date).status === 'few_left',
                                                          'text-red-600':     getData(cell.date).status === 'full',
                                                          'text-slate-500':   getData(cell.date).status === 'closed',
                                                      }"
                                                      x-text="statusShort(getData(cell.date).status)"></span>
                                            </div>
                                            <template x-if="getData(cell.date).total > 0">
                                                <span class="text-[9px] text-[#64748B] leading-none"
                                                      x-text="remaining(getData(cell.date)) + '/' + getData(cell.date).total"></span>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!getData(cell.date)">
                                        <span class="mt-auto text-[9px] text-[#64748B]/40 leading-none">—</span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Legend --}}
                <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-[#2F6F57]/8">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-[#64748B]">Status:</span>
                    <span class="flex items-center gap-1 text-[10px] text-emerald-700"><span class="status-dot dot-open"></span> Open</span>
                    <span class="flex items-center gap-1 text-[10px] text-amber-700"><span class="status-dot dot-few_left"></span> Few Left</span>
                    <span class="flex items-center gap-1 text-[10px] text-red-600"><span class="status-dot dot-full"></span> Full</span>
                    <span class="flex items-center gap-1 text-[10px] text-slate-500"><span class="status-dot dot-closed"></span> Closed</span>
                    <span class="flex items-center gap-1 text-[10px] text-slate-400 ml-1">
                        <span class="w-4 h-4 rounded border border-dashed border-slate-300 inline-block"></span> Not Set
                    </span>
                </div>
            </div>

        </div>

        {{-- ── Quick Set Sidebar ────────────────────────────────────────── --}}
        <div class="xl:col-span-4">
            <div class="sticky top-6 space-y-4">

                {{-- Quick Set Panel --}}
                <div class="glass-forest rounded-3xl p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#64748B]">Quick Set</h3>
                        <span x-show="selectedDates.length === 0"
                              class="text-[10px] text-[#64748B] font-light">Select dates on calendar</span>
                        <span x-show="selectedDates.length > 0"
                              class="inline-flex items-center gap-1 bg-[#2F6F57]/10 text-[#2F6F57] text-[10px] font-bold px-2 py-0.5 rounded-lg"
                              x-text="selectedDates.length + ' date' + (selectedDates.length > 1 ? 's' : '') + ' selected'"></span>
                    </div>

                    {{-- No selection placeholder --}}
                    <div x-show="selectedDates.length === 0"
                         class="rounded-2xl border border-dashed border-[#2F6F57]/20 py-8 text-center">
                        <i class="fa-regular fa-calendar-plus text-2xl text-[#2F6F57]/25 block mb-2"></i>
                        <p class="text-xs text-[#64748B] font-light">Click a date to select it,</p>
                        <p class="text-[11px] text-[#64748B] font-light">or use Range mode to bulk-select.</p>
                    </div>

                    {{-- Form (shown when dates selected) --}}
                    <div x-show="selectedDates.length > 0" x-cloak class="space-y-3">

                        {{-- Selected dates preview --}}
                        <div x-show="selectedDates.length <= 6" class="flex flex-wrap gap-1.5">
                            <template x-for="d in selectedDates" :key="d">
                                <span class="inline-flex items-center gap-1 bg-[#2F6F57]/8 text-[#2F6F57] text-[10px] font-semibold px-2 py-0.5 rounded-lg">
                                    <span x-text="formatDateLabel(d)"></span>
                                    <button type="button" @click="deselectDate(d)"
                                            class="text-[#2F6F57]/50 hover:text-[#2F6F57] leading-none ml-0.5">×</button>
                                </span>
                            </template>
                        </div>
                        <div x-show="selectedDates.length > 6"
                             class="text-[11px] text-[#64748B] font-light"
                             x-text="selectedDates[0] + ' → ' + selectedDates[selectedDates.length-1]"></div>

                        {{-- Status --}}
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-[#64748B] block mb-1.5">Status</span>
                            <div class="grid grid-cols-2 gap-2">
                                <template x-for="s in statuses" :key="s.value">
                                    <button type="button"
                                            @click="form.status = s.value"
                                            :class="form.status === s.value ? s.activeClass : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-200'"
                                            class="py-2 px-3 rounded-xl text-[11px] font-semibold transition-all flex items-center gap-1.5 border">
                                        <span class="status-dot" :class="'dot-' + s.value"></span>
                                        <span x-text="s.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Inventory --}}
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <div>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-[#64748B] block mb-1.5">Total Rooms</span>
                                <input type="number" class="fi" x-model.number="form.total" min="0" placeholder="0"
                                       @input="autoStatus()">
                            </div>
                            <div>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-[#64748B] block mb-1.5">Booked</span>
                                <input type="number" class="fi" x-model.number="form.booked" min="0" placeholder="0"
                                       @input="autoStatus()">
                            </div>
                        </div>
                        <div class="flex items-center justify-between bg-[#2F6F57]/5 rounded-xl px-3 py-2">
                            <span class="text-[10px] font-bold text-[#2F6F57]">Remaining</span>
                            <span class="text-sm font-bold text-[#2F6F57]"
                                  x-text="Math.max(0, (form.total || 0) - (form.booked || 0))"></span>
                        </div>

                        {{-- Auto-status hint --}}
                        <div x-show="form.total > 0"
                             class="text-[10px] text-[#64748B] font-light flex items-center gap-1">
                            <i class="fa-solid fa-bolt text-[#D4AF37] text-[9px]"></i>
                            Status auto-derived from inventory. Override above if needed.
                        </div>

                        {{-- Apply button --}}
                        <button type="button"
                                @click="applyToSelection()"
                                :disabled="saving"
                                class="w-full py-3 bg-[#2F6F57] text-white rounded-2xl text-sm font-bold hover:bg-[#255a46] active:scale-95 transition-all flex items-center justify-center gap-2 shadow-sm shadow-[#2F6F57]/20 disabled:opacity-60">
                            <template x-if="!saving">
                                <span><i class="fa-solid fa-floppy-disk mr-1.5"></i> Apply to Selection</span>
                            </template>
                            <template x-if="saving">
                                <span><i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Saving…</span>
                            </template>
                        </button>

                        {{-- Remove button --}}
                        <button type="button"
                                @click="removeFromSchedule()"
                                :disabled="saving"
                                x-show="selectedDates.some(d => getData(d))"
                                class="w-full py-2.5 border border-rose-200 text-rose-500 rounded-2xl text-xs font-semibold hover:bg-rose-50 active:scale-95 transition-all flex items-center justify-center gap-2 disabled:opacity-60">
                            <i class="fa-regular fa-trash-can text-[11px]"></i> Remove from Schedule
                        </button>

                        {{-- Save feedback --}}
                        <div x-show="saveMessage"
                             x-transition.opacity
                             :class="saveError ? 'bg-rose-50 text-rose-600 border-rose-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'"
                             class="rounded-xl border px-3 py-2 text-[11px] font-semibold flex items-center gap-2">
                            <i :class="saveError ? 'fa-solid fa-circle-exclamation' : 'fa-solid fa-circle-check'"></i>
                            <span x-text="saveMessage"></span>
                        </div>
                    </div>
                </div>

                {{-- Experience / Accommodation info card --}}
                <div class="glass-forest rounded-3xl p-5 space-y-3">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#64748B]">Managing</h3>
                    <div class="space-y-1">
                        <p class="text-sm font-bold text-[#1E2522] bb-serif">{{ $experience->name }}</p>
                        @foreach($accommodations as $accom)
                        @if($accom->id == $selectedAccomId)
                        <p class="text-xs text-[#2F6F57] font-semibold flex items-center gap-1.5">
                            <i class="fa-regular fa-bed text-[10px]"></i> {{ $accom->name }}
                        </p>
                        @endif
                        @endforeach
                    </div>
                    @php $configuredCount = $availabilityRows->count(); @endphp
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-[#2F6F57]/6 rounded-2xl p-3 text-center">
                            <p class="bb-serif text-xl font-semibold text-[#2F6F57]">{{ $configuredCount }}</p>
                            <p class="text-[9px] text-[#2F6F57] font-medium mt-0.5">Scheduled</p>
                        </div>
                        <div class="bg-[#D4AF37]/10 rounded-2xl p-3 text-center">
                            <p class="bb-serif text-xl font-semibold text-amber-700">
                                {{ $availabilityRows->where('status', 'open')->count() }}
                            </p>
                            <p class="text-[9px] text-amber-700 font-medium mt-0.5">Open</p>
                        </div>
                    </div>
                    <a href="{{ route('center-panel.availability.manage', $experience->id) }}"
                       class="w-full py-2 border border-[#2F6F57]/20 text-[#64748B] rounded-2xl text-xs font-semibold hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-tag text-[10px]"></i> Edit Pricing
                    </a>
                </div>

            </div>
        </div>

    </div>{{-- /main grid --}}

    {{-- ── Overview Matrix ─────────────────────────────────────────────────── --}}
    @if($uniqueDates->isNotEmpty() && $accommodations->count() > 1)
    <div class="glass-forest rounded-3xl p-5 space-y-4">
        <div>
            <h3 class="text-xs font-bold uppercase tracking-widest text-[#64748B]">All Accommodations Overview</h3>
            <p class="text-[11px] text-[#64748B] font-light mt-0.5">Availability across all room types for configured start dates.</p>
        </div>
        <div class="overflow-x-auto -mx-1">
            <table class="w-full text-xs border-separate border-spacing-1">
                <thead>
                    <tr>
                        <th class="text-left text-[9px] font-bold uppercase tracking-wider text-[#64748B] pb-2 px-1 whitespace-nowrap">Room Type</th>
                        @foreach($uniqueDates as $date)
                        <th class="text-[9px] font-bold text-[#64748B] pb-2 px-1 min-w-[52px] whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($date)->format('M j') }}
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($accommodations as $accom)
                    <tr>
                        <td class="py-1 px-1 text-[11px] font-semibold text-[#1E2522] whitespace-nowrap">{{ $accom->name }}</td>
                        @foreach($uniqueDates as $date)
                        @php $row = $matrix[$accom->id][$date] ?? null; @endphp
                        <td class="py-1 px-0.5">
                            @if($row)
                            <span class="matrix-cell matrix-{{ $row->status }} block" title="{{ ucfirst(str_replace('_',' ',$row->status)) }}: {{ $row->total_rooms - $row->booked_rooms }}/{{ $row->total_rooms }}">
                                {{ strtoupper(substr($row->status, 0, 1)) }}{{ $row->total_rooms > 0 ? ':'.max(0,$row->total_rooms-$row->booked_rooms) : '' }}
                            </span>
                            @else
                            <span class="matrix-cell matrix-none block">—</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-[10px] text-[#64748B] font-light">O=Open, F=Few Left, X=Full, C=Closed · number after colon = remaining rooms</p>
    </div>
    @endif

</div>{{-- /x-data --}}

@endsection

@section('scripts')
<script>
function calendarApp() {
    return {
        /* ── State ──────────────────────────────────────── */
        experienceId:    {{ $experience->id }},
        accommodationId: {{ $selectedAccomId }},
        data:            @json($availabilityData),

        year:  new Date().getFullYear(),
        month: new Date().getMonth(),   // 0-based

        mode:          'single', // 'single' | 'range'
        selectedDates: [],
        rangeStart:    null,
        hoverDate:     null,

        form: { status: 'open', total: 0, booked: 0 },
        saving:      false,
        saveMessage: '',
        saveError:   false,

        statuses: [
            { value: 'open',     label: 'Open',     activeClass: 'bg-emerald-500 text-white border-emerald-500' },
            { value: 'few_left', label: 'Few Left', activeClass: 'bg-amber-500 text-white border-amber-500' },
            { value: 'full',     label: 'Full',     activeClass: 'bg-red-500 text-white border-red-500' },
            { value: 'closed',   label: 'Closed',   activeClass: 'bg-slate-500 text-white border-slate-500' },
        ],

        /* ── Init ──────────────────────────────────────── */
        init() {
            // Normalise data keys (object from PHP JSON)
            const raw = this.data;
            this.data = {};
            if (raw && typeof raw === 'object') {
                Object.assign(this.data, raw);
            }
        },

        /* ── Computed: calendar cells ────────────────── */
        get calendarCells() {
            const cells = [];
            const firstDay = new Date(this.year, this.month, 1).getDay(); // 0=Sun
            const daysInMonth = new Date(this.year, this.month + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                cells.push({ key: 'e' + i, date: null, day: null });
            }
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = this.dateStr(this.year, this.month + 1, d);
                cells.push({ key: dateStr, date: dateStr, day: d });
            }
            return cells;
        },

        get monthLabel() {
            return new Date(this.year, this.month, 1)
                .toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        },

        /* ── Helpers ─────────────────────────────────── */
        dateStr(y, m, d) {
            return `${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        },
        getData(dateStr) {
            return this.data[dateStr] ?? null;
        },
        remaining(row) {
            return Math.max(0, (row.total || 0) - (row.booked || 0));
        },
        isToday(dateStr) {
            return dateStr === new Date().toISOString().slice(0, 10);
        },
        statusShort(s) {
            return { open: 'Open', few_left: 'Few', full: 'Full', closed: 'Closed' }[s] ?? s;
        },
        formatDateLabel(dateStr) {
            const d = new Date(dateStr + 'T00:00:00');
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        },
        selectionSummary() {
            if (this.selectedDates.length === 0) return 'Click a date to begin';
            if (this.selectedDates.length === 1) return '1 date selected';
            return this.selectedDates.length + ' dates selected';
        },

        /* ── Range helpers ───────────────────────────── */
        datesInRange(a, b) {
            const start = a < b ? a : b;
            const end   = a < b ? b : a;
            const result = [];
            const cur = new Date(start + 'T00:00:00');
            const fin = new Date(end   + 'T00:00:00');
            while (cur <= fin) {
                result.push(cur.toISOString().slice(0,10));
                cur.setDate(cur.getDate() + 1);
            }
            return result;
        },
        get hoverRange() {
            if (this.mode !== 'range' || !this.rangeStart || !this.hoverDate) return [];
            return this.datesInRange(this.rangeStart, this.hoverDate);
        },

        /* ── Cell CSS ────────────────────────────────── */
        cellClass(cell) {
            if (!cell.date) return 'cal-day empty';

            const d    = cell.date;
            const row  = this.getData(d);
            const base = 'cal-day ' + (row ? 'status-' + row.status : 'unset');

            const isSelected  = this.selectedDates.includes(d);
            const isRangeHov  = this.hoverRange.includes(d);
            const isRangeStart= this.rangeStart === d;
            const isRangeEnd  = this.hoverDate  === d && this.mode === 'range' && this.rangeStart;

            const classes = [base];
            if (isSelected && !isRangeStart) classes.push('selected');
            if (isRangeStart) classes.push('range-start');
            if (isRangeEnd)   classes.push('range-end');
            if (isRangeHov && !isRangeStart) classes.push('in-range');

            return classes.join(' ');
        },

        /* ── Click logic ─────────────────────────────── */
        clickDay(dateStr) {
            if (this.mode === 'single') {
                const idx = this.selectedDates.indexOf(dateStr);
                if (idx === -1) {
                    this.selectedDates.push(dateStr);
                } else {
                    this.selectedDates.splice(idx, 1);
                }
                this.selectedDates.sort();
                this.prefillForm();
            } else {
                // Range mode
                if (!this.rangeStart) {
                    this.rangeStart  = dateStr;
                    this.selectedDates = [];
                } else {
                    const range = this.datesInRange(this.rangeStart, dateStr);
                    this.selectedDates = range;
                    this.rangeStart = null;
                    this.hoverDate  = null;
                    this.prefillForm();
                }
            }
        },
        deselectDate(dateStr) {
            this.selectedDates = this.selectedDates.filter(d => d !== dateStr);
            if (this.selectedDates.length > 0) this.prefillForm();
        },
        clearSelection() {
            this.selectedDates = [];
            this.rangeStart    = null;
            this.hoverDate     = null;
        },

        /* ── Prefill form from first selected date ───── */
        prefillForm() {
            const first = this.selectedDates[0];
            if (!first) return;
            const row = this.getData(first);
            if (row) {
                this.form.status = row.status;
                this.form.total  = row.total;
                this.form.booked = row.booked;
            }
        },

        /* ── Auto-derive status from inventory ───────── */
        autoStatus() {
            const total     = this.form.total || 0;
            const booked    = this.form.booked || 0;
            const remaining = Math.max(0, total - booked);
            if (total <= 0) { this.form.status = 'open'; return; }
            if (remaining === 0) { this.form.status = 'full'; return; }
            if (remaining / total <= 0.20) { this.form.status = 'few_left'; return; }
            this.form.status = 'open';
        },

        /* ── Month navigation ───────────────────────── */
        prevMonth() {
            if (this.month === 0) { this.month = 11; this.year--; }
            else this.month--;
        },
        nextMonth() {
            if (this.month === 11) { this.month = 0; this.year++; }
            else this.month++;
        },

        /* ── AJAX: Apply to selection ─────────────────── */
        async applyToSelection() {
            if (!this.selectedDates.length || this.saving) return;
            this.saving      = true;
            this.saveMessage = '';
            this.saveError   = false;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            let ok = 0, fail = 0;

            for (const dateStr of this.selectedDates) {
                try {
                    const res = await fetch('{{ route("center-panel.availability.schedule.update") }}', {
                        method:  'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept':       'application/json',
                        },
                        body: JSON.stringify({
                            experience_id:    this.experienceId,
                            accommodation_id: this.accommodationId,
                            start_date:       dateStr,
                            status:           this.form.status,
                            total_rooms:      this.form.total  || 0,
                            booked_rooms:     this.form.booked || 0,
                        }),
                    });
                    if (res.ok) {
                        const json = await res.json();
                        this.data[dateStr] = {
                            id:     json.id,
                            status: json.status,
                            total:  json.total,
                            booked: json.booked,
                        };
                        ok++;
                    } else { fail++; }
                } catch (e) { fail++; }
            }

            this.saving = false;
            if (fail === 0) {
                this.saveMessage = ok + ' date' + (ok > 1 ? 's' : '') + ' saved successfully.';
            } else {
                this.saveError   = true;
                this.saveMessage = ok + ' saved, ' + fail + ' failed. Please retry.';
            }
            setTimeout(() => { this.saveMessage = ''; this.saveError = false; }, 4000);
        },

        /* ── AJAX: Remove from schedule ──────────────── */
        async removeFromSchedule() {
            if (!this.selectedDates.length || this.saving) return;
            if (!confirm('Remove schedule data for ' + this.selectedDates.length + ' date(s)?')) return;

            this.saving      = true;
            this.saveMessage = '';
            this.saveError   = false;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            let ok = 0, fail = 0;

            for (const dateStr of this.selectedDates) {
                const row = this.getData(dateStr);
                if (!row) continue;
                try {
                    const res = await fetch('{{ route("center-panel.availability.schedule.delete") }}', {
                        method:  'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept':       'application/json',
                        },
                        body: JSON.stringify({ id: row.id }),
                    });
                    if (res.ok) {
                        delete this.data[dateStr];
                        ok++;
                    } else { fail++; }
                } catch (e) { fail++; }
            }

            this.saving = false;
            if (fail === 0) {
                this.saveMessage = ok + ' date' + (ok > 1 ? 's' : '') + ' removed.';
                this.selectedDates = this.selectedDates.filter(d => !this.getData(d) && this.data[d]);
            } else {
                this.saveError   = true;
                this.saveMessage = ok + ' removed, ' + fail + ' failed.';
            }
            setTimeout(() => { this.saveMessage = ''; this.saveError = false; }, 4000);
        },
    };
}
</script>
@endsection
