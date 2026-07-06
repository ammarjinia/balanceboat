@extends('layouts.center')

@section('title', 'Bookings Ledger — BalanceBoat')

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    .ledger-scope {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #fcfbfa;
    }
    .ledger-scope .font-serif {
        font-family: 'Instrument Serif', Georgia, serif;
    }
    .ledger-scope .glass-premium {
        background: rgba(255, 255, 255, 0.45);
        backdrop-filter: blur(40px);
        -webkit-backdrop-filter: blur(40px);
    }
    .ledger-scope .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .ledger-scope .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.02); border-radius: 10px; }
    .ledger-scope .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0, 0, 0, 0.1); border-radius: 10px; }
    .ledger-scope .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(139, 92, 246, 0.3); }
    .ledger-scope .stage-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: none;
    }
</style>
@endsection

@section('content')

    <div class="ledger-scope relative rounded-[32px] p-4 sm:p-6 -m-5 md:-m-8 overflow-hidden">

        {{-- BALANCEBOAT SIGNATURE AMBIENT GLOW FIELDS --}}
        <div class="absolute top-[-20%] right-[-10%] w-[50vw] h-[50vw] max-w-[600px] max-h-[600px] bg-indigo-100/40 blur-[130px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[45vw] h-[45vw] max-w-[550px] max-h-[550px] bg-orange-50/50 blur-[120px] rounded-full pointer-events-none"></div>

        {{-- TOAST NOTIFICATION LOUVER --}}
        <div id="toast-notification" class="fixed top-6 right-6 transform translate-y-[-20px] opacity-0 pointer-events-none bg-neutral-900 text-white text-xs font-mono py-3 px-5 rounded-xl shadow-xl transition-all duration-300 z-50 flex items-center gap-2">
            <span>✨</span> <span id="toast-message">Ledger State Synced Safely</span>
        </div>

        {{-- MAIN OS CONTAINER --}}
        <div class="w-full min-h-[80vh] rounded-[32px] border border-white/60 shadow-[0_32px_96px_-16px_rgba(140,120,100,0.12)] glass-premium p-5 sm:p-6 flex flex-col justify-between relative z-10">

            {{-- HEADER MODULE & QUICK STATS --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-neutral-200/50">
                <div>
                    <div class="inline-flex items-center gap-2 px-2.5 py-0.5 bg-white/80 border border-neutral-200/60 rounded-full shadow-xs mb-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[8px] font-mono tracking-[0.2em] uppercase text-neutral-500 font-bold">
                            🔒 System Registry // Yield Ledger
                        </span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-serif font-light italic leading-tight text-neutral-900">
                        Ecosystem <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-orange-400 font-normal">Bookings Ledger</span>
                    </h2>
                </div>

                {{-- CONCISE HIGH-YIELD OVERVIEW METRICS --}}
                <div class="grid grid-cols-3 gap-3 sm:gap-6 bg-white/60 px-4 py-2 rounded-2xl border border-white/80 shadow-xs">
                    <div class="text-left">
                        <span class="text-[8px] font-mono uppercase tracking-wider text-neutral-400 block font-bold">Gross Volume</span>
                        <span id="stat-gross" class="text-sm font-semibold text-neutral-900 font-mono">—</span>
                    </div>
                    <div class="text-left border-l border-neutral-200/60 pl-3 sm:pl-6">
                        <span class="text-[8px] font-mono uppercase tracking-wider text-neutral-400 block font-bold">Avg. Commission</span>
                        <span id="stat-comm" class="text-sm font-semibold text-violet-600 font-mono">—</span>
                    </div>
                    <div class="text-left border-l border-neutral-200/60 pl-3 sm:pl-6">
                        <span class="text-[8px] font-mono uppercase tracking-wider text-neutral-400 block font-bold">Total Discounts</span>
                        <span id="stat-discount" class="text-sm font-semibold text-emerald-600 font-mono">—</span>
                    </div>
                </div>
            </div>

            {{-- SEARCH, FILTERS & ACTIONS PANEL --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 py-3 items-center">
                <div class="md:col-span-3 relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-xs pointer-events-none text-neutral-400">🔍</span>
                    <input type="text" id="search-input" onkeyup="filterLedgerEngine()" placeholder="Search Seeker, Sanctuary..."
                           class="w-full pl-8 pr-3 py-2 bg-white/80 border border-neutral-200/60 rounded-xl text-xs placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-violet-400/50 transition-all shadow-2xs">
                </div>

                <div class="md:col-span-3">
                    <select id="filter-retreat" onchange="filterLedgerEngine()"
                            class="w-full px-3 py-2 bg-white/80 border border-neutral-200/60 rounded-xl text-xs text-neutral-600 focus:outline-none focus:ring-1 focus:ring-violet-400/50 transition-all shadow-2xs">
                        <option value="ALL">All Retreat Sanctuaries</option>
                        @foreach($experiences as $exp)
                            <option value="{{ $exp->name }}">{{ $exp->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <select id="filter-status" onchange="filterLedgerEngine()"
                            class="w-full px-3 py-2 bg-white/80 border border-neutral-200/60 rounded-xl text-xs text-neutral-600 focus:outline-none focus:ring-1 focus:ring-violet-400/50 transition-all shadow-2xs">
                        <option value="ALL">All Statuses</option>
                        @foreach($stages as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 col-span-2">
                    <button type="button" onclick="exportLedgerToExcel()"
                            class="w-full py-2 px-2 rounded-xl font-mono text-[9px] font-bold tracking-wider uppercase text-neutral-600 bg-white border border-neutral-200 hover:bg-neutral-50 active:scale-[0.99] shadow-xs transition-all flex items-center justify-center gap-1.5">
                        📊 Export
                    </button>
                </div>

                <div class="md:col-span-2 col-span-2">
                    <button type="button" onclick="toggleModalVisibility(true)"
                            class="w-full py-2 px-2 rounded-xl font-mono text-[9px] font-bold tracking-wider uppercase text-white bg-neutral-900 hover:bg-neutral-800 active:scale-[0.99] shadow-md shadow-neutral-950/5 transition-all flex items-center justify-center gap-1">
                        ➕ Add Manual Entry
                    </button>
                </div>
            </div>

            {{-- MAIN DATA TABLE LAYER --}}
            <div class="flex-1 overflow-y-auto overflow-x-auto custom-scrollbar border border-neutral-200/40 rounded-2xl bg-white/70 shadow-2xs mb-2">
                <table class="w-full text-left border-collapse min-w-[950px]">
                    <thead>
                        <tr class="bg-neutral-50/70 border-b border-neutral-200/50 text-[9px] font-mono tracking-wider uppercase text-neutral-400 sticky top-0 z-20 backdrop-blur-md">
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Seeker Customer</th>
                            <th class="py-3 px-4">Sanctuary & Room Framework</th>
                            <th class="py-3 px-4 text-center">Pax</th>
                            <th class="py-3 px-4">Dates (Book / In)</th>
                            <th class="py-3 px-4 text-right">Gross Price</th>
                            <th class="py-3 px-4 text-right">BB Discount</th>
                            <th class="py-3 px-4 text-center">Comm. %</th>
                            <th class="py-3 px-4 text-center">Context Matrix</th>
                        </tr>
                    </thead>
                    <tbody id="ledger-rows" class="divide-y divide-neutral-100 text-xs">
                        {{-- Dynamic Data Injected via JavaScript Engine --}}
                    </tbody>
                </table>
            </div>

            {{-- FOOTER BAR / STATUS --}}
            <div class="flex items-center justify-between text-[9px] font-mono text-neutral-400 uppercase tracking-widest pt-2 border-t border-neutral-100">
                <span>BalanceBoat OS Framework v2.2.0</span>
                <span id="entry-count">Showing 0 entries</span>
            </div>

        </div>

        {{-- DYNAMIC POP-IN FORM OVERLAY MODAL --}}
        <div id="booking-modal" class="hidden fixed inset-0 bg-neutral-900/30 backdrop-blur-xs flex items-center justify-center z-50 p-4 transition-all duration-300">
            <div class="bg-white/95 rounded-[28px] border border-white/80 shadow-[0_24px_64px_rgba(0,0,0,0.15)] w-full max-w-xl max-h-[85vh] overflow-y-auto custom-scrollbar flex flex-col">

                <div class="p-5 border-b border-neutral-100 flex items-center justify-between sticky top-0 bg-white/90 backdrop-blur-md z-10">
                    <div>
                        <span class="text-[8px] font-mono tracking-widest text-violet-500 font-bold uppercase block mb-0.5">Ecosystem Interlock</span>
                        <h3 class="text-xl font-serif font-light italic text-neutral-900">Synchronize New Reservation</h3>
                    </div>
                    <button type="button" onclick="toggleModalVisibility(false)" class="text-neutral-400 hover:text-neutral-600 text-lg p-1 font-mono">&times;</button>
                </div>

                <form id="ledger-submission-form" method="POST" action="{{ route('center-panel.bookings.store') }}" class="p-5 space-y-4 flex-1">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-rose-50 border border-rose-200 rounded-xl px-3 py-2 text-[11px] text-rose-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Customer Seeker Name</label>
                            <input type="text" name="name" required placeholder="e.g. Sophia Alcott" value="{{ old('name') }}" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-neutral-50/50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Target Sanctuary</label>
                            <select id="in-retreat" name="experience_id" required onchange="syncRoomOptions()" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-neutral-50/50">
                                <option value="">Select a retreat…</option>
                                @foreach($experiences as $exp)
                                    <option value="{{ $exp->id }}" {{ old('experience_id') == $exp->id ? 'selected' : '' }}>{{ $exp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Room / Suite Category</label>
                            <select id="in-room" name="accommodation_id" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-neutral-50/50">
                                <option value="">Select a retreat first…</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Pax Capacity Count</label>
                            <input type="number" name="pax" min="1" max="50" required value="{{ old('pax', 1) }}" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-neutral-50/50">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Booking Date Signature</label>
                            <input type="date" id="in-date-book" name="booking_date" required value="{{ old('booking_date') }}" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-neutral-50/50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Arrival Check-In Target</label>
                            <input type="date" name="check_in_date" required value="{{ old('check_in_date') }}" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-neutral-50/50">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Gross Price ($)</label>
                            <input type="number" name="gross_amount" min="0" step="0.01" required placeholder="8500" value="{{ old('gross_amount') }}" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-neutral-50/50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">BB Discount ($)</label>
                            <input type="number" name="discount" min="0" step="0.01" value="{{ old('discount', 0) }}" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-neutral-50/50">
                        </div>
                        <div>
                            <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Commission % Matrix</label>
                            <select name="commission_pct" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-neutral-50/50">
                                <option value="15">15% (Baseline)</option>
                                <option value="20">20%</option>
                                <option value="25">25%</option>
                                <option value="30">30%</option>
                                <option value="35">35%</option>
                                <option value="40">40%</option>
                                <option value="45">45% (Sovereign)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Current Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-neutral-50/50">
                            @foreach($stages as $key => $label)
                                <option value="{{ $key }}" {{ old('status', 'confirmed') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Extra Requirements / Amenities Logistics</label>
                        <textarea name="extra_details" rows="2" placeholder="e.g. Airport shuttle transfers, private deck custom wellness set..." class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-neutral-50/50 resize-none">{{ old('extra_details') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-[9px] font-mono font-bold text-violet-400 uppercase tracking-wider mb-1">Internal Sanctuary Host Notes</label>
                        <textarea name="host_notes" rows="2" placeholder="e.g. Profile flags priority alignment, dietary track checks..." class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-neutral-50/50 resize-none">{{ old('host_notes') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" onclick="toggleModalVisibility(false)" class="flex-1 py-2.5 rounded-xl border border-neutral-200 text-neutral-500 font-mono text-[10px] font-bold uppercase tracking-wider hover:bg-neutral-50 transition-all">Cancel</button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-neutral-900 text-white font-mono text-[10px] font-bold uppercase tracking-wider hover:bg-neutral-800 transition-all shadow-md">Save Booking</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const stageLabels = @json($stages);
    const updateStageUrlTemplate = "{{ route('center-panel.bookings.update_stage', ['id' => '__ID__']) }}";
    const accommodationsByExperience = {!! $accommodationsByExperienceJson !!};

    // Real bookings for this center, assembled server-side
    let globalLedgerDatabase = {!! $ledgerJson !!};

    const stageColors = {
        inquiry:       "bg-neutral-100 text-neutral-600 border-neutral-200",
        qualified:     "bg-cyan-50 text-cyan-700 border-cyan-200/60",
        proposal_sent: "bg-amber-50 text-amber-700 border-amber-200/60",
        confirmed:     "bg-emerald-50 text-emerald-700 border-emerald-200/60",
        checked_in:    "bg-indigo-50 text-indigo-700 border-indigo-200/60",
        completed:     "bg-neutral-100 text-neutral-600 border-neutral-200",
        cancelled:     "bg-rose-50 text-rose-600 border-rose-200/60",
        refunded:      "bg-rose-50 text-rose-600 border-rose-200/60",
    };

    function renderLedgerTable(dataset) {
        const container = document.getElementById('ledger-rows');
        container.innerHTML = "";

        if (dataset.length === 0) {
            container.innerHTML = `<tr><td colspan="9" class="py-8 text-center text-neutral-400 italic font-serif text-sm">No records match your selected parameters.</td></tr>`;
            document.getElementById('entry-count').innerText = "0 Records Matched";
            return;
        }

        dataset.forEach((row) => {
            const stageOptions = Object.keys(stageLabels).map(key =>
                `<option value="${key}" ${key === row.status ? 'selected' : ''}>${stageLabels[key]}</option>`
            ).join('');
            const colorClass = stageColors[row.status] || stageColors.inquiry;

            const elementRow = document.createElement('tr');
            elementRow.className = "hover:bg-neutral-50/50 transition-all group border-b border-neutral-100";
            elementRow.dataset.bookingId = row.id;

            const pax = (row.pax === null || row.pax === undefined) ? '—' : row.pax;
            const commission = (row.commissionPct === null || row.commissionPct === undefined) ? '—' : `${row.commissionPct}%`;
            const currency = row.currency || '';

            elementRow.innerHTML = `
                <td class="py-3 px-4 font-mono" onclick="event.stopPropagation()">
                    <select class="stage-select text-[10px] font-semibold px-2 py-1 rounded border cursor-pointer ${colorClass}" onchange="updateBookingStage(${row.id}, this.value)">
                        ${stageOptions}
                    </select>
                </td>
                <td class="py-3 px-4 font-medium text-neutral-900 cursor-pointer" onclick="toggleContextDetails(${row.id})">${row.customerName}</td>
                <td class="py-3 px-4 cursor-pointer" onclick="toggleContextDetails(${row.id})">
                    <span class="italic text-neutral-700 font-serif text-sm block">${row.retreatName}</span>
                    <span class="text-[10px] font-sans text-neutral-400 font-semibold tracking-wide flex items-center gap-1 mt-0.5">🛋️ ${row.roomCategory}</span>
                </td>
                <td class="py-3 px-4 text-center font-mono font-bold text-neutral-500 cursor-pointer" onclick="toggleContextDetails(${row.id})">${pax}</td>
                <td class="py-3 px-4 text-xs font-mono text-neutral-500 cursor-pointer" onclick="toggleContextDetails(${row.id})">
                    <span class="block text-neutral-400">B: ${row.bookingDate || '—'}</span>
                    <span class="block text-neutral-800 font-medium">C: ${row.checkInDate || '—'}</span>
                </td>
                <td class="py-3 px-4 text-right font-mono font-semibold text-neutral-900 cursor-pointer" onclick="toggleContextDetails(${row.id})">${currency} ${Number(row.grossAmount).toLocaleString()}</td>
                <td class="py-3 px-4 text-right font-mono text-emerald-600 cursor-pointer" onclick="toggleContextDetails(${row.id})">${row.discount > 0 ? `-${currency} ${Number(row.discount).toLocaleString()}` : '—'}</td>
                <td class="py-3 px-4 text-center font-mono cursor-pointer" onclick="toggleContextDetails(${row.id})"><span class="px-1.5 py-0.5 bg-violet-50 border border-violet-100 rounded text-violet-600 font-bold">${commission}</span></td>
                <td class="py-3 px-4 text-center">
                    <button type="button" onclick="toggleContextDetails(${row.id})" class="text-neutral-400 group-hover:text-violet-500 font-mono text-[10px] uppercase border border-neutral-200 rounded px-2 py-0.5 bg-white shadow-3xs transition-all">
                        Details ↓
                    </button>
                </td>
            `;
            container.appendChild(elementRow);

            const elementDetailRow = document.createElement('tr');
            elementDetailRow.id = `detail-pane-${row.id}`;
            elementDetailRow.className = "hidden bg-neutral-50/40 border-l-2 border-violet-400";
            elementDetailRow.innerHTML = `
                <td colspan="9" class="p-4 bg-white/80 border-b border-neutral-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200/40">
                            <span class="text-[8px] font-mono tracking-widest text-neutral-400 block font-bold uppercase mb-1">🛎️ Seeker Extra Upgrade Details</span>
                            <p class="text-xs text-neutral-700 leading-relaxed">${row.extraDetails || 'No extra requirements specified.'}</p>
                        </div>
                        <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200/40">
                            <span class="text-[8px] font-mono tracking-widest text-violet-400 block font-bold uppercase mb-1">📝 Internal Sanctuary Host Notes</span>
                            <p class="text-xs text-neutral-600 italic font-serif text-[13px] leading-relaxed">${row.notes || 'No operational host notes logged.'}</p>
                        </div>
                    </div>
                </td>
            `;
            container.appendChild(elementDetailRow);
        });

        document.getElementById('entry-count').innerText = `Showing ${dataset.length} Sync Entries`;
        recomputeYieldAnalyticsMetrics(dataset);
    }

    function recomputeYieldAnalyticsMetrics(dataset) {
        let totalGross = 0;
        let totalDiscounts = 0;
        let commSum = 0;
        let commCount = 0;

        dataset.forEach(item => {
            totalGross += Number(item.grossAmount) || 0;
            totalDiscounts += Number(item.discount) || 0;
            if (item.commissionPct !== null && item.commissionPct !== undefined) {
                commSum += Number(item.commissionPct);
                commCount++;
            }
        });

        const avgComm = commCount > 0 ? (commSum / commCount).toFixed(1) : '—';

        document.getElementById('stat-gross').innerText = `${totalGross.toLocaleString()}`;
        document.getElementById('stat-comm').innerText = commCount > 0 ? `${avgComm}% Avg` : '—';
        document.getElementById('stat-discount').innerText = `-${totalDiscounts.toLocaleString()}`;
    }

    function showToastNotification(message) {
        const toast = document.getElementById('toast-notification');
        document.getElementById('toast-message').innerText = message;

        toast.classList.remove('opacity-0', 'translate-y-[-20px]', 'pointer-events-none');
        toast.classList.add('opacity-100', 'translate-y-0');

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-[-20px]', 'pointer-events-none');
            toast.classList.remove('opacity-100', 'translate-y-0');
        }, 2500);
    }

    function toggleModalVisibility(shouldOpen) {
        const modal = document.getElementById('booking-modal');
        if (shouldOpen) {
            modal.classList.remove('hidden');
            const bookDateInput = document.getElementById('in-date-book');
            if (!bookDateInput.value) bookDateInput.value = new Date().toISOString().split('T')[0];
            syncRoomOptions();
        } else {
            modal.classList.add('hidden');
        }
    }

    function syncRoomOptions() {
        const expId = document.getElementById('in-retreat').value;
        const roomSelect = document.getElementById('in-room');
        const rooms = accommodationsByExperience[expId] || [];

        if (!expId) {
            roomSelect.innerHTML = '<option value="">Select a retreat first…</option>';
            return;
        }
        if (rooms.length === 0) {
            roomSelect.innerHTML = '<option value="">No accommodations configured</option>';
            return;
        }
        roomSelect.innerHTML = rooms.map(r => `<option value="${r.id}">${r.name}</option>`).join('');
    }

    function toggleContextDetails(id) {
        const pane = document.getElementById(`detail-pane-${id}`);
        if (pane) pane.classList.toggle('hidden');
    }

    async function updateBookingStage(bookingId, newStage) {
        try {
            const res = await fetch(updateStageUrlTemplate.replace('__ID__', bookingId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ stage: newStage }),
            });
            const data = await res.json();
            if (data.success) {
                const item = globalLedgerDatabase.find(r => r.id === bookingId);
                if (item) item.status = newStage;
                showToastNotification(`Status updated to ${data.label}`);
            } else {
                showToastNotification('Could not update status');
            }
        } catch (e) {
            showToastNotification('Network error updating status');
        }
    }

    function filterLedgerEngine() {
        const textQuery = document.getElementById('search-input').value.toLowerCase();
        const retreatSelection = document.getElementById('filter-retreat').value;
        const statusSelection = document.getElementById('filter-status').value;

        const filteredData = globalLedgerDatabase.filter(item => {
            const textMatch = item.customerName.toLowerCase().includes(textQuery) || item.retreatName.toLowerCase().includes(textQuery) || item.roomCategory.toLowerCase().includes(textQuery);
            const retreatMatch = (retreatSelection === "ALL") || (item.retreatName === retreatSelection);
            const statusMatch = (statusSelection === "ALL") || (item.status === statusSelection);

            return textMatch && retreatMatch && statusMatch;
        });

        renderLedgerTable(filteredData);
    }

    function exportLedgerToExcel() {
        let csvRows = [];
        const headers = ["Booking ID", "Status", "Customer Name", "Retreat Name", "Room Category", "Pax Count", "Booking Date", "Check-In Date", "Currency", "Gross Amount", "BB Discount", "Commission Applied (%)", "Extra Details", "Host Notes"];
        csvRows.push(headers.map(h => `"${h}"`).join(","));

        globalLedgerDatabase.forEach(item => {
            const data = [
                item.id,
                stageLabels[item.status] || item.status,
                item.customerName,
                item.retreatName,
                item.roomCategory,
                item.pax ?? '',
                item.bookingDate,
                item.checkInDate,
                item.currency,
                item.grossAmount,
                item.discount,
                item.commissionPct ?? '',
                (item.extraDetails || '').replace(/"/g, '""'),
                (item.notes || '').replace(/"/g, '""'),
            ];
            csvRows.push(data.map(field => `"${field}"`).join(","));
        });

        const csvContent = "data:text/csv;charset=utf-8," + csvRows.join("\n");
        const encodedUri = encodeURI(csvContent);
        const downloadTrigger = document.createElement("a");
        downloadTrigger.setAttribute("href", encodedUri);
        downloadTrigger.setAttribute("download", `balanceboat_ledger_export.csv`);
        document.body.appendChild(downloadTrigger);
        downloadTrigger.click();
        document.body.removeChild(downloadTrigger);
    }

    document.getElementById('booking-modal')?.addEventListener('click', function (e) {
        if (e.target === this) toggleModalVisibility(false);
    });

    renderLedgerTable(globalLedgerDatabase);

    @if (session('success'))
        showToastNotification(@json(session('success')));
    @endif
    @if ($errors->any())
        toggleModalVisibility(true);
    @endif
</script>
@endsection
