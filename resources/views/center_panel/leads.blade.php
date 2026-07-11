@extends('layouts.center')

@section('title', 'Lead Pipeline & CRM Command — BalanceBoat')

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
    .ledger-scope .stage-select { appearance: none; -webkit-appearance: none; background-image: none; }
    .ledger-scope .respond-ping { animation: crmPulse 1.8s infinite alternate; }
    @keyframes crmPulse { 0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.45); } 100% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); } }
</style>
@endsection

@section('content')

    <div class="ledger-scope relative rounded-[32px] p-4 sm:p-6 -m-5 md:-m-8 overflow-hidden">

        {{-- BALANCEBOAT SIGNATURE AMBIENT GLOW FIELDS --}}
        <div class="absolute top-[-20%] right-[-10%] w-[50vw] h-[50vw] max-w-[600px] max-h-[600px] bg-indigo-100/40 blur-[130px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[45vw] h-[45vw] max-w-[550px] max-h-[550px] bg-orange-50/50 blur-[120px] rounded-full pointer-events-none"></div>

        {{-- TOAST NOTIFICATION --}}
        <div id="toast-notification" class="fixed top-6 right-6 transform translate-y-[-20px] opacity-0 pointer-events-none bg-neutral-900 text-white text-xs font-mono py-3 px-5 rounded-xl shadow-xl transition-all duration-300 z-50 flex items-center gap-2">
            <span>✨</span> <span id="toast-message">Pipeline State Synced Safely</span>
        </div>

        {{-- MAIN OS CONTAINER --}}
        <div class="w-full min-h-[80vh] rounded-[32px] border border-white/60 shadow-[0_32px_96px_-16px_rgba(140,120,100,0.12)] glass-premium p-5 sm:p-6 flex flex-col justify-between relative z-10">

            {{-- HEADER MODULE --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-neutral-200/50">
                <div>
                    <div class="inline-flex items-center gap-2 px-2.5 py-0.5 bg-white/80 border border-neutral-200/60 rounded-full shadow-xs mb-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[8px] font-mono tracking-[0.2em] uppercase text-neutral-500 font-bold">
                            🔒 System Registry // Inquiry Ledger
                        </span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-serif font-light italic leading-tight text-neutral-900">
                        Lead <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-orange-400 font-normal">Pipeline & CRM Command</span>
                    </h2>
                    <p class="text-[11px] text-neutral-500 mt-1">Track response statuses, log outbound communications, and advance stages live.</p>
                </div>
            </div>

            {{-- LIVE ALERT BANNER --}}
            <div id="alert-banner" class="hidden items-center gap-3 bg-rose-50 border border-rose-200/60 rounded-2xl px-4 py-3 my-3">
                <span class="text-lg">🚨</span>
                <div class="text-[12.5px] font-medium text-rose-800 flex-1">
                    <strong>Attention Required:</strong> <span id="banner-pending-count">0</span> incoming lead inquiries need an immediate response.
                </div>
            </div>

            {{-- KPI GRID --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 py-3">
                <div class="bg-white/70 border border-neutral-200/50 rounded-2xl p-3.5 shadow-2xs">
                    <span class="text-[9px] font-mono uppercase tracking-wider text-neutral-400 font-bold flex items-center gap-1">👥 Total Live Leads</span>
                    <div id="kpi-leads-count" class="text-xl font-bold text-neutral-900 mt-1">0</div>
                </div>
                <div class="bg-white/70 border border-neutral-200/50 rounded-2xl p-3.5 shadow-2xs">
                    <span class="text-[9px] font-mono uppercase tracking-wider text-neutral-400 font-bold flex items-center gap-1">💎 Weighted Pipeline</span>
                    <div id="kpi-pipe-value" class="text-xl font-bold text-emerald-600 mt-1">—</div>
                </div>
                <div class="bg-white/70 border border-neutral-200/50 rounded-2xl p-3.5 shadow-2xs">
                    <span class="text-[9px] font-mono uppercase tracking-wider text-neutral-400 font-bold flex items-center gap-1">🏆 Closed Won Value</span>
                    <div id="kpi-won-value" class="text-xl font-bold text-violet-600 mt-1">—</div>
                </div>
                <div class="bg-white/70 border border-neutral-200/50 rounded-2xl p-3.5 shadow-2xs">
                    <span class="text-[9px] font-mono uppercase tracking-wider text-neutral-400 font-bold flex items-center gap-1">⏱️ Avg Response Speed</span>
                    <div id="kpi-response-speed" class="text-xl font-bold text-neutral-900 mt-1">—</div>
                </div>
                <div class="bg-white/70 border border-neutral-200/50 rounded-2xl p-3.5 shadow-2xs">
                    <span class="text-[9px] font-mono uppercase tracking-wider text-neutral-400 font-bold flex items-center gap-1">📥 External Ingestions</span>
                    <div id="kpi-external-count" class="text-xl font-bold text-cyan-600 mt-1">0</div>
                </div>
            </div>

            {{-- SEARCH & FILTERS --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 py-2 items-center">
                <div class="md:col-span-4 relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-xs pointer-events-none text-neutral-400">🔍</span>
                    <input type="text" id="search-input" onkeyup="filterLeadsEngine()" placeholder="Search prospect, retreat…"
                           class="w-full pl-8 pr-3 py-2 bg-white/80 border border-neutral-200/60 rounded-xl text-xs placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-violet-400/50 transition-all shadow-2xs">
                </div>
                <div class="md:col-span-3">
                    <select id="filter-retreat" onchange="filterLeadsEngine()" class="w-full px-3 py-2 bg-white/80 border border-neutral-200/60 rounded-xl text-xs text-neutral-600 focus:outline-none focus:ring-1 focus:ring-violet-400/50 transition-all shadow-2xs">
                        <option value="ALL">All Retreat Programs</option>
                        @foreach($experiences as $exp)
                            <option value="{{ $exp->name }}">{{ $exp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3">
                    <select id="filter-stage" onchange="filterLeadsEngine()" class="w-full px-3 py-2 bg-white/80 border border-neutral-200/60 rounded-xl text-xs text-neutral-600 focus:outline-none focus:ring-1 focus:ring-violet-400/50 transition-all shadow-2xs">
                        <option value="ALL">All Stages</option>
                        @foreach($stages as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <button type="button" onclick="exportLeadsToCsv()" class="w-full py-2 px-2 rounded-xl font-mono text-[9px] font-bold tracking-wider uppercase text-neutral-600 bg-white border border-neutral-200 hover:bg-neutral-50 active:scale-[0.99] shadow-xs transition-all flex items-center justify-center gap-1.5">
                        📊 Export
                    </button>
                </div>
            </div>

            {{-- WORKSPACE TWO-COLUMN SPLIT --}}
            <div class="grid grid-cols-1 lg:grid-cols-[1.6fr_0.9fr] gap-5 mt-2">

                {{-- LEFT COLUMN --}}
                <div class="space-y-5 min-w-0">

                    {{-- PIPELINE DIRECTORY TABLE --}}
                    <div class="border border-neutral-200/40 rounded-2xl bg-white/70 shadow-2xs overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-200/50">
                            <span class="text-[13px] font-bold text-neutral-800 flex items-center gap-2">📋 Live Lead Allocation Directory</span>
                            <span class="text-[10px] text-neutral-400">💡 Expand a row to respond</span>
                        </div>
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse min-w-[820px]">
                                <thead>
                                    <tr class="bg-neutral-50/70 border-b border-neutral-200/50 text-[9px] font-mono tracking-wider uppercase text-neutral-400">
                                        <th class="py-3 px-4">Prospect</th>
                                        <th class="py-3 px-4">Retreat Program</th>
                                        <th class="py-3 px-4">Source</th>
                                        <th class="py-3 px-4">Stage</th>
                                        <th class="py-3 px-4 text-right">Value</th>
                                        <th class="py-3 px-4 text-center">Score</th>
                                    </tr>
                                </thead>
                                <tbody id="leads-rows" class="divide-y divide-neutral-100 text-xs">
                                    {{-- Dynamic rows injected via JS --}}
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-between text-[9px] font-mono text-neutral-400 uppercase tracking-widest px-4 py-2.5 border-t border-neutral-100">
                            <span>BalanceBoat CRM Engine v1.0.0</span>
                            <span id="entry-count">Showing 0 entries</span>
                        </div>
                    </div>

                    {{-- INGEST MANUAL LEAD --}}
                    <div class="border border-dashed border-cyan-300/60 bg-cyan-50/10 rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[13px] font-bold text-neutral-800 flex items-center gap-2">📥 Ingest External CRM Record</span>
                            <span class="text-[9px] font-mono uppercase tracking-wider text-cyan-700 bg-cyan-50 border border-cyan-200/60 rounded-full px-2 py-0.5">⚙️ Manual Entry</span>
                        </div>

                        @if ($errors->any())
                            <div class="bg-rose-50 border border-rose-200 rounded-xl px-3 py-2 text-[11px] text-rose-700 mb-3">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('center-panel.leads.store') }}" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Prospect Name</label>
                                    <input type="text" name="name" required placeholder="e.g. Julian Brandt" value="{{ old('name') }}" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-white/70">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Email</label>
                                    <input type="email" name="email" required placeholder="julian@example.com" value="{{ old('email') }}" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-white/70">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Phone</label>
                                    <input type="text" name="phone" placeholder="e.g. 9876543210" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-white/70">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Target Retreat Program</label>
                                    <select name="experience_id" required class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-white/70">
                                        <option value="">Select a retreat…</option>
                                        @foreach($experiences as $exp)
                                            <option value="{{ $exp->id }}" {{ old('experience_id') == $exp->id ? 'selected' : '' }}>{{ $exp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Value Allocation ({{ trim($currencySymbol) }})</label>
                                <input type="number" name="deal_value" min="0" step="0.01" placeholder="e.g. 45000" value="{{ old('deal_value') }}" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-white/70">
                            </div>
                            <div>
                                <label class="block text-[9px] font-mono font-bold text-neutral-400 uppercase tracking-wider mb-1">Notes / Context</label>
                                <textarea name="message" rows="2" placeholder="e.g. Referred by past guest, interested in group booking…" class="w-full px-3 py-2 border border-neutral-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-violet-400/50 bg-white/70 resize-none">{{ old('message') }}</textarea>
                            </div>
                            <button type="submit" class="w-full py-2.5 rounded-xl bg-neutral-900 text-white font-mono text-[10px] font-bold uppercase tracking-wider hover:bg-neutral-800 transition-all shadow-md">
                                💾 Commit Record to Live Pipeline
                            </button>
                        </form>
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="space-y-5 min-w-0">
                    <div class="border border-neutral-200/40 rounded-2xl bg-white/70 shadow-2xs p-4">
                        <span class="text-[13px] font-bold text-neutral-800 flex items-center gap-2 mb-3">⚠️ Immediate Tactical Tasks</span>
                        <ul id="tactical-tasks" class="space-y-3">
                            {{-- Dynamic tasks injected via JS --}}
                        </ul>
                    </div>

                    <div class="border border-neutral-200/40 rounded-2xl bg-white/70 shadow-2xs p-4">
                        <span class="text-[13px] font-bold text-neutral-800 flex items-center gap-2 mb-3">🎯 Pipeline Quality Metrics</span>
                        <div class="rounded-xl border border-emerald-100 bg-gradient-to-br from-emerald-50/40 to-cyan-50/40 px-3 py-2.5 flex items-center justify-between mb-2">
                            <span class="text-[11px] font-semibold text-neutral-600">🛡️ Contact Data Accuracy</span>
                            <span id="quality-accuracy" class="text-lg font-bold text-emerald-600">—</span>
                        </div>
                        <p class="text-[11.5px] text-neutral-500 leading-relaxed">
                            Share of leads with a verified phone number on file. Leads missing contact data are systematically flagged for follow-up.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const stageLabels = @json($stages);
    const currencySymbol = @json(trim($currencySymbol));
    const updateStageUrlTemplate = "{{ route('center-panel.leads.update_stage', ['id' => '__ID__']) }}";
    const respondUrlTemplate = "{{ route('center-panel.leads.respond', ['id' => '__ID__']) }}";

    let globalLeadsDatabase = {!! $leadsJson !!};

    const stageColors = {
        new:           "bg-neutral-100 text-neutral-600 border-neutral-200",
        proposal_sent: "bg-amber-50 text-amber-700 border-amber-200/60",
        negotiation:   "bg-cyan-50 text-cyan-700 border-cyan-200/60",
        won:           "bg-emerald-50 text-emerald-700 border-emerald-200/60",
        lost:          "bg-rose-50 text-rose-600 border-rose-200/60",
    };

    const stageWeights = { new: 9, proposal_sent: 18, negotiation: 27, won: 30, lost: 0 };

    function computeScore(value, sourceTag, stage) {
        const valueScore = Math.min((Number(value) / 100000) * 40, 40);
        const sourceScore = sourceTag === 'BalanceBoat' ? 30 : 18;
        const stageScore = stageWeights[stage] !== undefined ? stageWeights[stage] : 9;
        return Math.round(valueScore + sourceScore + stageScore);
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.innerText = str ?? '';
        return div.innerHTML;
    }

    function renderLeadsTable(dataset) {
        const container = document.getElementById('leads-rows');
        container.innerHTML = "";

        if (dataset.length === 0) {
            container.innerHTML = `<tr><td colspan="6" class="py-8 text-center text-neutral-400 italic font-serif text-sm">No leads match your selected parameters.</td></tr>`;
            document.getElementById('entry-count').innerText = "0 Records Matched";
        } else {
            dataset.forEach((row) => {
                const stageOptions = Object.keys(stageLabels).map(key =>
                    `<option value="${key}" ${key === row.stage ? 'selected' : ''}>${stageLabels[key]}</option>`
                ).join('');
                const colorClass = stageColors[row.stage] || stageColors.new;
                const score = computeScore(row.dealValue, row.sourceTag, row.stage);
                const needsResponse = row.stage === 'new';
                const badgeClass = row.sourceTag === 'BalanceBoat' ? 'bg-violet-50 text-violet-700 border-violet-200/60' : 'bg-cyan-50 text-cyan-700 border-cyan-200/60';
                const badgeIcon = row.sourceTag === 'BalanceBoat' ? '🪐' : '🔗';

                const mainRow = document.createElement('tr');
                mainRow.className = `hover:bg-neutral-50/50 transition-all cursor-pointer border-b border-neutral-100 ${needsResponse ? 'border-l-4 border-l-rose-400' : ''}`;
                mainRow.onclick = () => toggleLeadDetails(row.id);
                mainRow.innerHTML = `
                    <td class="py-3 px-4 font-semibold text-neutral-900">
                        ${needsResponse ? '<span class="inline-block h-2 w-2 rounded-full bg-rose-500 respond-ping mr-1.5"></span>' : ''}${escapeHtml(row.name)}
                        <br><span class="text-[10.5px] text-neutral-400 font-normal">${escapeHtml(row.email)}</span>
                    </td>
                    <td class="py-3 px-4 text-neutral-700 italic font-serif">${escapeHtml(row.retreatName)}</td>
                    <td class="py-3 px-4"><span class="px-2 py-0.5 rounded-full border text-[10px] font-semibold ${badgeClass}">${badgeIcon} ${row.sourceTag}</span></td>
                    <td class="py-3 px-4" onclick="event.stopPropagation()">
                        <select class="stage-select text-[10px] font-semibold px-2 py-1 rounded border cursor-pointer ${colorClass}" onchange="handleStageChange(${row.id}, this)">
                            ${stageOptions}
                        </select>
                    </td>
                    <td class="py-3 px-4 text-right font-mono font-bold text-neutral-900">${currencySymbol}${Number(row.dealValue).toLocaleString()}</td>
                    <td class="py-3 px-4 text-center"><span class="inline-block min-w-[36px] px-2 py-0.5 rounded-md bg-neutral-900 text-white font-mono text-[11px] font-bold">${score}</span></td>
                `;
                container.appendChild(mainRow);

                const detailRow = document.createElement('tr');
                detailRow.id = `detail-pane-${row.id}`;
                detailRow.className = "hidden bg-neutral-50/40";
                detailRow.innerHTML = `
                    <td colspan="6" class="p-4 bg-white/80 border-b border-l-2 border-l-violet-400 border-neutral-100">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
                            <div class="p-2.5 bg-neutral-50 rounded-xl border border-neutral-200/40">
                                <span class="text-[8px] font-mono tracking-widest text-neutral-400 block font-bold uppercase mb-1">📞 Phone</span>
                                <p class="text-xs text-neutral-700 font-medium">${row.phone ? escapeHtml(row.phone) : '—'}</p>
                            </div>
                            <div class="p-2.5 bg-neutral-50 rounded-xl border border-neutral-200/40">
                                <span class="text-[8px] font-mono tracking-widest text-neutral-400 block font-bold uppercase mb-1">🧬 Retreat Program</span>
                                <p class="text-xs text-neutral-700 font-medium">${escapeHtml(row.retreatName)}</p>
                            </div>
                            <div class="p-2.5 bg-neutral-50 rounded-xl border border-neutral-200/40">
                                <span class="text-[8px] font-mono tracking-widest text-neutral-400 block font-bold uppercase mb-1">⏳ Ingested Timestamp</span>
                                <p class="text-xs text-neutral-700 font-medium">${row.createdAt || '—'}</p>
                            </div>
                        </div>

                        ${row.message ? `
                        <div class="p-2.5 bg-neutral-50 rounded-xl border border-neutral-200/40 mb-3">
                            <span class="text-[8px] font-mono tracking-widest text-neutral-400 block font-bold uppercase mb-1">💬 Original Inquiry Message</span>
                            <p class="text-xs text-neutral-600 whitespace-pre-line leading-relaxed">${escapeHtml(row.message)}</p>
                        </div>` : ''}

                        <div class="bg-violet-50/60 border border-violet-200/40 rounded-xl p-3.5 mb-3">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-violet-600 block mb-2">⚡ Direct Center Response Console</label>
                            <div class="flex gap-2">
                                <input type="text" id="reply-text-${row.id}" placeholder="Type your reply to this prospect…" class="flex-1 px-3 py-2 border border-neutral-200 rounded-xl text-xs bg-white focus:outline-none focus:ring-1 focus:ring-violet-400/50">
                                <button type="button" onclick="dispatchCenterResponse(${row.id})" class="px-3.5 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-[11px] font-semibold whitespace-nowrap transition-all">✉️ Dispatch Reply</button>
                            </div>
                        </div>

                        <div class="p-2.5 bg-neutral-50 rounded-xl border border-neutral-200/40">
                            <span class="text-[8px] font-mono tracking-widest text-neutral-400 block font-bold uppercase mb-1">📝 Center Response Log & Internal Notes</span>
                            <p id="notes-${row.id}" class="text-xs text-neutral-600 whitespace-pre-line leading-relaxed">${row.note ? escapeHtml(row.note) : 'No communication logged yet.'}</p>
                        </div>

                        <div id="loss-box-${row.id}" class="mt-3 bg-white border border-dashed border-rose-200 rounded-xl p-3 ${row.stage === 'lost' ? '' : 'hidden'}">
                            <strong class="text-rose-500 text-[10.5px]">🛑 Reason for Loss</strong>
                            <p id="loss-text-${row.id}" class="text-xs italic text-neutral-700 mt-1">${row.lossReason ? escapeHtml(row.lossReason) : 'None documented.'}</p>
                        </div>
                    </td>
                `;
                container.appendChild(detailRow);
            });
            document.getElementById('entry-count').innerText = `Showing ${dataset.length} entries`;
        }

        recomputeLeadMetrics(dataset);
    }

    function recomputeLeadMetrics(dataset) {
        const totalLeads = dataset.length;
        const openPipeline = dataset.filter(d => d.stage !== 'won' && d.stage !== 'lost').reduce((s, d) => s + Number(d.dealValue), 0);
        const wonValue = dataset.filter(d => d.stage === 'won').reduce((s, d) => s + Number(d.dealValue), 0);
        const externalCount = dataset.filter(d => d.sourceTag === 'External').length;
        const pendingCount = dataset.filter(d => d.stage === 'new').length;

        const responded = dataset.filter(d => d.responseMinutes !== null && d.responseMinutes !== undefined);
        let responseLabel = '—';
        if (responded.length > 0) {
            const avgMin = responded.reduce((s, d) => s + Number(d.responseMinutes), 0) / responded.length;
            responseLabel = avgMin >= 60 ? `${(avgMin / 60).toFixed(1)} hrs` : `${Math.round(avgMin)} mins`;
        }

        const withPhone = dataset.filter(d => d.phone && d.phone.length > 0).length;
        const accuracy = totalLeads > 0 ? Math.round((withPhone / totalLeads) * 100) : 0;

        document.getElementById('kpi-leads-count').innerText = totalLeads;
        document.getElementById('kpi-pipe-value').innerText = `${currencySymbol}${openPipeline.toLocaleString()}`;
        document.getElementById('kpi-won-value').innerText = `${currencySymbol}${wonValue.toLocaleString()}`;
        document.getElementById('kpi-response-speed').innerText = responseLabel;
        document.getElementById('kpi-external-count').innerText = externalCount;
        document.getElementById('quality-accuracy').innerText = `${accuracy}%`;

        const banner = document.getElementById('alert-banner');
        if (pendingCount > 0) {
            banner.classList.remove('hidden');
            banner.classList.add('flex');
            document.getElementById('banner-pending-count').innerText = pendingCount;
        } else {
            banner.classList.add('hidden');
            banner.classList.remove('flex');
        }

        renderTacticalTasks(dataset);
    }

    function renderTacticalTasks(dataset) {
        const list = document.getElementById('tactical-tasks');
        const tasks = [];

        dataset.filter(d => d.stage === 'new').sort((a, b) => (b.ageHours ?? 0) - (a.ageHours ?? 0)).slice(0, 3).forEach(d => {
            tasks.push({
                icon: '⏳', color: 'text-rose-500',
                title: `${d.name} — SLA Alert`,
                desc: `Sitting unmanaged for ${d.ageHours ?? '—'} hour(s). Respond to protect first-contact metrics.`,
            });
        });

        dataset.filter(d => d.stage === 'proposal_sent' || d.stage === 'negotiation').slice(0, 3).forEach(d => {
            tasks.push({
                icon: '📝', color: 'text-amber-500',
                title: `Follow-up Due: ${d.name}`,
                desc: `Proposal in "${stageLabels[d.stage]}" — trigger a nudge to keep momentum.`,
            });
        });

        if (tasks.length === 0) {
            list.innerHTML = `<li class="text-[12px] text-neutral-400 italic font-serif">All caught up — no urgent actions pending.</li>`;
            return;
        }

        list.innerHTML = tasks.slice(0, 5).map(t => `
            <li class="flex gap-2.5">
                <div class="${t.color} text-sm">${t.icon}</div>
                <div>
                    <div class="text-[12px] font-semibold text-neutral-800">${escapeHtml(t.title)}</div>
                    <div class="text-[11px] text-neutral-500 leading-snug">${escapeHtml(t.desc)}</div>
                </div>
            </li>
        `).join('');
    }

    function showToast(message) {
        const toast = document.getElementById('toast-notification');
        document.getElementById('toast-message').innerText = message;
        toast.classList.remove('opacity-0', 'translate-y-[-20px]', 'pointer-events-none');
        toast.classList.add('opacity-100', 'translate-y-0');
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-[-20px]', 'pointer-events-none');
            toast.classList.remove('opacity-100', 'translate-y-0');
        }, 2500);
    }

    function toggleLeadDetails(id) {
        const pane = document.getElementById(`detail-pane-${id}`);
        if (pane) pane.classList.toggle('hidden');
    }

    async function handleStageChange(id, selectEl) {
        const newStage = selectEl.value;
        const item = globalLeadsDatabase.find(r => r.id === id);
        let lossReason = null;

        if (newStage === 'lost') {
            lossReason = prompt("⚠️ Document the reason for marking this lead as 'Lost':");
            if (!lossReason || lossReason.trim() === "") {
                selectEl.value = item.stage;
                return;
            }
        }

        try {
            const res = await fetch(updateStageUrlTemplate.replace('__ID__', id), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ stage: newStage, loss_reason: lossReason }),
            });
            const data = await res.json();
            if (data.success) {
                item.stage = data.stage;
                item.lossReason = lossReason || item.lossReason;
                showToast(`Stage updated to ${data.label}`);
                renderLeadsTable(getFilteredDataset());
            } else {
                selectEl.value = item.stage;
                showToast('Could not update stage');
            }
        } catch (e) {
            selectEl.value = item.stage;
            showToast('Network error updating stage');
        }
    }

    async function dispatchCenterResponse(id) {
        const input = document.getElementById(`reply-text-${id}`);
        const message = input.value.trim();
        if (!message) {
            showToast('Type a message before dispatching');
            return;
        }

        try {
            const res = await fetch(respondUrlTemplate.replace('__ID__', id), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ message }),
            });
            const data = await res.json();
            if (data.success) {
                const item = globalLeadsDatabase.find(r => r.id === id);
                item.note = data.note;
                item.stage = data.stage;
                if (item.responseMinutes === null || item.responseMinutes === undefined) item.responseMinutes = 1;
                input.value = '';
                showToast('Reply dispatched — pipeline updated');
                renderLeadsTable(getFilteredDataset());
                setTimeout(() => { const p = document.getElementById(`detail-pane-${id}`); if (p) p.classList.remove('hidden'); }, 50);
            } else {
                showToast('Could not dispatch reply');
            }
        } catch (e) {
            showToast('Network error dispatching reply');
        }
    }

    function getFilteredDataset() {
        const textQuery = document.getElementById('search-input').value.toLowerCase();
        const retreatSelection = document.getElementById('filter-retreat').value;
        const stageSelection = document.getElementById('filter-stage').value;

        return globalLeadsDatabase.filter(item => {
            const textMatch = item.name.toLowerCase().includes(textQuery) || item.retreatName.toLowerCase().includes(textQuery) || item.email.toLowerCase().includes(textQuery);
            const retreatMatch = (retreatSelection === "ALL") || (item.retreatName === retreatSelection);
            const stageMatch = (stageSelection === "ALL") || (item.stage === stageSelection);
            return textMatch && retreatMatch && stageMatch;
        });
    }

    function filterLeadsEngine() {
        renderLeadsTable(getFilteredDataset());
    }

    function exportLeadsToCsv() {
        const headers = ["Lead ID", "Stage", "Name", "Email", "Phone", "Retreat", "Source", "Value", "Ingested"];
        const rows = [headers.map(h => `"${h}"`).join(",")];

        globalLeadsDatabase.forEach(item => {
            const data = [item.id, stageLabels[item.stage] || item.stage, item.name, item.email, item.phone, item.retreatName, item.sourceTag, item.dealValue, item.createdAt];
            rows.push(data.map(f => `"${String(f ?? '').replace(/"/g, '""')}"`).join(","));
        });

        const encodedUri = encodeURI("data:text/csv;charset=utf-8," + rows.join("\n"));
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "balanceboat_leads_export.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    renderLeadsTable(globalLeadsDatabase);

    @if (session('success'))
        showToast(@json(session('success')));
    @endif
</script>
@endsection
