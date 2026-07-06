@extends('layouts.center')

@section('title', 'Commission Engine — ' . $experience->name)

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    .commission-engine-scope {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .commission-engine-scope .font-serif {
        font-family: 'Instrument Serif', Georgia, serif;
    }
    /* Custom Premium Slider Styling */
    .commission-engine-scope input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #ffffff;
        cursor: pointer;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.3), 0 4px 10px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .commission-engine-scope input[type="range"]::-webkit-slider-thumb:hover {
        transform: scale(1.15);
        box-shadow: 0 15px 30px -5px rgba(139, 92, 246, 0.45);
    }
    .commission-engine-scope .glass-premium {
        background: rgba(255, 255, 255, 0.45);
        backdrop-filter: blur(40px);
        -webkit-backdrop-filter: blur(40px);
    }
    .commission-engine-scope .glass-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
</style>
@endsection

@section('content')

    {{-- Breadcrumb --}}
    <div class="border-b border-slate-200 pb-6 space-y-3">
        <nav class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('center-panel.commission') }}" class="hover:text-purple-600 transition-colors">Commission Engine</a>
            <i class="fa-solid fa-chevron-right text-[9px]"></i>
            <span class="text-slate-900 font-semibold">{{ $experience->name }}</span>
        </nav>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-emerald-700">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-rose-700">
            <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="commission-engine-scope relative rounded-[40px] overflow-hidden">

        {{-- BALANCEBOAT SIGNATURE AMBIENT GLOW FIELDS --}}
        <div id="glow-lavender" class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] max-w-[600px] max-h-[600px] bg-indigo-200/40 blur-[120px] rounded-full pointer-events-none transition-all duration-1000 ease-in-out"></div>
        <div id="glow-peach" class="absolute bottom-[-10%] right-[-10%] w-[45vw] h-[45vw] max-w-[550px] max-h-[550px] bg-orange-100/50 blur-[130px] rounded-full pointer-events-none transition-all duration-1000 ease-in-out"></div>
        <div id="glow-pink" class="absolute top-[30%] right-[15%] w-[35vw] h-[35vw] max-w-[420px] max-h-[420px] bg-pink-100/40 blur-[100px] rounded-full pointer-events-none transition-all duration-1000 ease-in-out"></div>

        {{-- MAIN OS CONTROLLER PANEL --}}
        <form method="POST" action="{{ route('center-panel.commission.save', $experience->id) }}"
              class="w-full rounded-[40px] border border-white/60 shadow-[0_32px_96px_-16px_rgba(140,120,100,0.12)] glass-premium p-6 sm:p-10 lg:p-12 relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            @csrf
            <input type="hidden" id="commission-value-input" name="commission" value="{{ $currentCommission }}">

            {{-- LEFT CONTROL PANEL --}}
            <div class="lg:col-span-5 flex flex-col justify-between space-y-10">
                <div class="space-y-5">
                    <?php /*<div class="inline-flex items-center gap-2 px-3 py-1 bg-white/80 border border-neutral-200/60 rounded-full shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-violet-400 animate-pulse"></span>
                        <span class="text-[9px] font-mono tracking-[0.2em] uppercase text-neutral-500 font-bold flex items-center gap-1">
                            🔑 BalanceBoat Center OS // Module V2
                        </span>
                    </div>*/?>
                    <h2 class="text-4xl lg:text-5xl font-serif font-light italic leading-[1.15] text-neutral-900">
                        Calibrate Your <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 via-pink-500 to-orange-400 font-normal">Abundance Framework</span>
                    </h2>
                    <p class="text-xs text-neutral-500 leading-relaxed max-w-sm">
                        Align your ecosystem commission structure to systematically scale platform concierge recommendations, elevate brand trust markers, and precisely sync matching algorithms.
                    </p>
                </div>

                {{-- PREMIUM INPUT MODULE --}}
                <div class="space-y-6 bg-white/60 p-6 rounded-3xl border border-white/80 shadow-sm">
                    <div class="flex justify-between items-end">
                        <span class="text-[10px] font-mono tracking-widest text-neutral-400 font-bold uppercase flex items-center gap-1">
                            📊 Commission Alignment
                        </span>
                        <span id="display-percentage" class="text-3xl font-serif text-violet-600 font-bold italic transition-all duration-300">{{ $currentCommission }}%</span>
                    </div>

                    <div class="relative pt-2">
                        <input type="range" id="main-slider" min="0" max="6" step="1" value="{{ $closestIndex }}" oninput="matrixEngineUpdate(this.value)"
                               class="w-full h-1 bg-neutral-200 rounded-lg appearance-none cursor-pointer focus:outline-none">
                        <div class="flex justify-between text-[9px] font-mono text-neutral-400 font-bold pt-3 uppercase tracking-wider">
                            <span class="flex items-center gap-1">🌱 Baseline Discovery</span>
                            <span class="flex items-center gap-1">👑 Sovereign Max</span>
                        </div>
                    </div>

                    {{-- PRESET BUTTON CORES --}}
                    <div class="grid grid-cols-4 sm:grid-cols-7 gap-1 pt-1">
                        <button type="button" onclick="matrixEngineUpdate(0)" id="preset-0" class="py-2 bg-white text-neutral-500 border border-neutral-200/50 rounded-xl text-xs font-mono font-bold hover:bg-neutral-50 transition-all">15%</button>
                        <button type="button" onclick="matrixEngineUpdate(1)" id="preset-1" class="py-2 bg-white text-neutral-500 border border-neutral-200/50 rounded-xl text-xs font-mono font-bold hover:bg-neutral-50 transition-all">20%</button>
                        <button type="button" onclick="matrixEngineUpdate(2)" id="preset-2" class="py-2 bg-white text-neutral-500 border border-neutral-200/50 rounded-xl text-xs font-mono font-bold hover:bg-neutral-50 transition-all">25%</button>
                        <button type="button" onclick="matrixEngineUpdate(3)" id="preset-3" class="py-2 bg-white text-neutral-500 border border-neutral-200/50 rounded-xl text-xs font-mono font-bold hover:bg-neutral-50 transition-all">30%</button>
                        <button type="button" onclick="matrixEngineUpdate(4)" id="preset-4" class="py-2 bg-white text-neutral-500 border border-neutral-200/50 rounded-xl text-xs font-mono font-bold hover:bg-neutral-50 transition-all">35%</button>
                        <button type="button" onclick="matrixEngineUpdate(5)" id="preset-5" class="py-2 bg-white text-neutral-500 border border-neutral-200/50 rounded-xl text-xs font-mono font-bold hover:bg-neutral-50 transition-all">40%</button>
                        <button type="button" onclick="matrixEngineUpdate(6)" id="preset-6" class="py-2 bg-white text-neutral-500 border border-neutral-200/50 rounded-xl text-xs font-mono font-bold hover:bg-neutral-50 transition-all">45%</button>
                    </div>
                </div>
            </div>

            {{-- RIGHT OUTPUT STATUS PANEL --}}
            <div class="lg:col-span-7 bg-white/40 border border-white/80 rounded-[32px] p-6 sm:p-8 flex flex-col justify-between space-y-6 shadow-sm relative overflow-hidden">

                {{-- TOP CARD HEADER BAR --}}
                <div class="flex items-center justify-between border-b border-neutral-200/60 pb-4">
                    <span class="text-[10px] font-mono tracking-widest text-neutral-400 font-bold uppercase flex items-center gap-1">
                        🗺️ Placement Identity
                    </span>
                    <span id="tier-badge" class="text-[10px] font-mono font-bold tracking-widest uppercase bg-white text-neutral-600 px-3 py-1 rounded-full border border-neutral-200/60 shadow-sm transition-all duration-300">
                        BASELINE DISCOVERY
                    </span>
                </div>

                {{-- PUBLIC SEEKER PREVIEW SIMULATOR --}}
                <div class="space-y-2.5">
                    <span class="text-[9px] font-mono tracking-widest text-neutral-400 block font-bold uppercase flex items-center gap-1">
                        👁️ Marketplace Badge Output
                    </span>
                    <div class="bg-white/80 rounded-2xl p-4 border border-neutral-200/40 flex items-center justify-between min-h-[64px] transition-all duration-500 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-neutral-50 flex items-center justify-center border border-neutral-200/40 text-sm shadow-inner">🌿</div>
                            <div>
                                <h4 class="text-xs font-bold text-neutral-800">{{ $experience->name }}</h4>
                                <p class="text-[10px] text-neutral-400">{{ $center->name ?? 'Your Center' }} · Live Preview</p>
                            </div>
                        </div>
                        <div>
                            <span id="customer-label" class="px-3 py-1 text-[10px] tracking-wide rounded-lg transition-all duration-500 bg-neutral-50 text-neutral-400 border border-neutral-200 border-dashed italic">
                                No Customer Label
                            </span>
                        </div>
                    </div>
                </div>

                {{-- THE SOUL VISION (HOST ASPIRATIONS) --}}
                <div class="space-y-1">
                    <span class="text-[9px] font-mono tracking-widest text-violet-500 block font-bold uppercase flex items-center gap-1">
                        ✨ The Soul Vision
                    </span>
                    <h3 id="hope-title" class="text-2xl font-serif text-neutral-900 italic font-light transition-all duration-300">Laying down the roots</h3>
                    <p id="hope-desc" class="text-xs text-neutral-500 leading-relaxed transition-all duration-500">
                        Plant your flag safely in our system registry. Allow your sacred space to be organically found by mindful individuals searching via manual custom-tailored search coordinates.
                    </p>
                </div>

                {{-- CORE CONCIERGE RECOMMENDATION SYSTEM ENGINE --}}
                <div class="p-5 rounded-2xl bg-white border border-neutral-200/60 space-y-2.5 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-base" id="concierge-icon">🧭</span>
                        <span class="text-[9px] font-mono tracking-widest text-emerald-600 font-bold uppercase">Concierge Endorsement Mode</span>
                    </div>
                    <p id="concierge-desc" class="text-xs text-neutral-600 leading-relaxed transition-all duration-500">
                        <strong id="concierge-tier-name" class="text-neutral-900 block mb-0.5 font-semibold transition-all duration-300">Organic Self-Discovery Loop</strong>
                        Displayed non-invasively inside our multi-category platform. Recommended purely when travelers construct highly granular matching filters manually.
                    </p>
                </div>

                {{-- SYSTEM FACTOR CONFIGURATION METRICS GRID --}}
                <div class="pt-2 border-t border-neutral-100">
                    <span class="text-[9px] font-mono tracking-widest text-neutral-400 block font-bold uppercase mb-3 flex items-center gap-1">
                        ⚙️ System Factor Configuration
                    </span>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

                        <div class="p-3 bg-white/60 rounded-xl border border-neutral-200/40 flex flex-col space-y-1 shadow-2xs">
                            <div class="flex items-center gap-1.5 text-neutral-700">
                                <span>📈</span>
                                <span class="text-[9px] font-mono uppercase tracking-wider font-bold text-neutral-400">Visibility</span>
                            </div>
                            <span id="metric-visibility" class="text-xs font-semibold text-neutral-800 transition-all">Passive</span>
                        </div>

                        <div class="p-3 bg-white/60 rounded-xl border border-neutral-200/40 flex flex-col space-y-1 shadow-2xs">
                            <div class="flex items-center gap-1.5 text-neutral-700">
                                <span>🎯</span>
                                <span class="text-[9px] font-mono uppercase tracking-wider font-bold text-neutral-400">Targeting</span>
                            </div>
                            <span id="metric-targeting" class="text-xs font-semibold text-neutral-800 transition-all">User Filtered</span>
                        </div>

                        <div class="p-3 bg-white/60 rounded-xl border border-neutral-200/40 flex flex-col space-y-1 shadow-2xs">
                            <div class="flex items-center gap-1.5 text-neutral-700">
                                <span>🧬</span>
                                <span class="text-[9px] font-mono uppercase tracking-wider font-bold text-neutral-400">Match Sync</span>
                            </div>
                            <span id="metric-sync" class="text-xs font-semibold text-neutral-800 transition-all">Basic Index</span>
                        </div>

                        <div class="p-3 bg-white/60 rounded-xl border border-neutral-200/40 flex flex-col space-y-1 shadow-2xs">
                            <div class="flex items-center gap-1.5 text-neutral-700">
                                <span>🌊</span>
                                <span class="text-[9px] font-mono uppercase tracking-wider font-bold text-neutral-400">Placements</span>
                            </div>
                            <span id="metric-placement" class="text-xs font-semibold text-neutral-800 transition-all">Standard</span>
                        </div>

                    </div>
                </div>

                {{-- PREMIUM LUXURY CALL TO ACTION BUTTON --}}
                <div class="pt-2">
                    <button type="submit" id="action-submit-btn"
                            class="w-full py-4 rounded-2xl font-mono text-[10px] font-bold tracking-[0.2em] uppercase text-white bg-neutral-900 shadow-lg shadow-neutral-950/10 hover:scale-[1.01] active:scale-[0.99] transition-all duration-300 ease-out">
                        ✨ Activate Organic Alignment
                    </button>
                </div>

            </div>
        </form>
    </div>

@endsection

@section('scripts')
<script>
    const scaleIndices = [15, 20, 25, 30, 35, 40, 45];

    const transformationMatrix = {
        15: {
            badge: "Baseline Discovery Track",
            label: "No Customer Label",
            labelStyle: "bg-neutral-50 text-neutral-400 border-neutral-200 border-dashed italic",
            hopeTitle: "Laying down the roots",
            hopeDesc: "Plant your flag safely in our system registry. Allow your sacred space to be organically found by mindful individuals searching via manual custom-tailored search coordinates.",
            conciergeIcon: "🧭",
            conciergeTitle: "Organic Discovery Indexing",
            conciergeDesc: "Displayed non-invasively inside our multi-category platform. Recommended purely when travelers construct highly granular matching filters manually.",
            metrics: { vis: "Standard", tar: "User Filtered", sync: "Basic Index", plac: "Directory Only" },
            colors: { lav: "rgba(224, 231, 255, 0.4)", pea: "rgba(255, 237, 213, 0.5)", pin: "rgba(252, 231, 243, 0.4)" },
            btnClass: "bg-neutral-900 text-white shadow-sm",
            actionLabel: "✨ Activate Organic Alignment",
            actionBtnStyle: "bg-neutral-900 hover:bg-neutral-800 shadow-neutral-950/10"
        },
        20: {
            badge: "Trust & Validation Layer",
            label: "✓ Verified Sanctuary",
            labelStyle: "bg-cyan-50 text-cyan-700 border-cyan-200/80 font-bold shadow-sm",
            hopeTitle: "Forging deep validation",
            hopeDesc: "Eradicate initial digital reservation barriers seamlessly. Instill an unshakeable sense of certified authenticity the split-second a seeking traveler encounters your listing configuration.",
            conciergeIcon: "🛡️",
            conciergeTitle: "Verified Trust Recommendation",
            conciergeDesc: "Our concierge layer dynamically matches and suggests your space to customers filtering specifically for high-security, authenticated premium wellness environments.",
            metrics: { vis: "+15% Lift", tar: "Curated Safety", sync: "Trust Filtered", plac: "Verified Pools" },
            colors: { lav: "rgba(207, 250, 254, 0.5)", pea: "rgba(254, 243, 199, 0.4)", pin: "rgba(224, 242, 254, 0.4)" },
            btnClass: "bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-md shadow-cyan-500/20",
            actionLabel: "🛡️ Lock In Trust Architecture",
            actionBtnStyle: "bg-gradient-to-r from-cyan-500 to-blue-500 shadow-cyan-500/20"
        },
        25: {
            badge: "Community Acceleration Track",
            label: "✨ Traveler Favorite",
            labelStyle: "bg-emerald-50 text-emerald-700 border-emerald-200/80 font-bold shadow-sm",
            hopeTitle: "Capturing community hearts",
            hopeDesc: "Become a highlighted, viral node in modern wellness social circles. Smoothly turn casual dashboard catalog saves into firm, high-vibe booking actions.",
            conciergeIcon: "💖",
            conciergeTitle: "Mindful Circle Endorsement",
            conciergeDesc: "The curation algorithm automatically populates your retreat into high-intent discovery streams when travelers ask for top-rated community-loved frameworks.",
            metrics: { vis: "+30% Lift", tar: "Social Validation", sync: "Affinity Match", plac: "Community Feeds" },
            colors: { lav: "rgba(209, 250, 229, 0.5)", pea: "rgba(254, 243, 199, 0.4)", pin: "rgba(243, 244, 246, 0.4)" },
            btnClass: "bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-md shadow-emerald-500/20",
            actionLabel: "💖 Commit To Community Track",
            actionBtnStyle: "bg-gradient-to-r from-emerald-500 to-teal-500 shadow-emerald-500/20"
        },
        30: {
            badge: "Amplified Footprint Grid",
            label: "👍 Highly Recommended",
            labelStyle: "bg-indigo-50 text-indigo-700 border-indigo-200/80 font-bold shadow-sm",
            hopeTitle: "Rising above the noise",
            hopeDesc: "Break completely clean from generic directory saturation. Establish an absolute premium footprint on our search matrix as a foundational pillar of modern transformation.",
            conciergeIcon: "🌟",
            conciergeTitle: "Curated Itinerary Spotlights",
            conciergeDesc: "Your sanctuary is recommended as a highly recommended anchor location whenever clients utilize our bespoke, multi-destination wellness planner features.",
            metrics: { vis: "+50% Lift", tar: "Premium Planners", sync: "Intent Syncing", plac: "Bespoke Guides" },
            colors: { lav: "rgba(224, 231, 255, 0.6)", pea: "rgba(254, 215, 170, 0.3)", pin: "rgba(253, 242, 248, 0.4)" },
            btnClass: "bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-md shadow-indigo-500/20",
            actionLabel: "🌟 Deploy Itinerary Spotlights",
            actionBtnStyle: "bg-gradient-to-r from-indigo-500 to-purple-500 shadow-indigo-500/20"
        },
        35: {
            badge: "Ecosystem Integration Node",
            label: "💎 BalanceBoat Choice",
            labelStyle: "bg-violet-50 text-violet-700 border-violet-200/80 font-bold shadow-sm",
            hopeTitle: "Attracting your true soulmatch",
            hopeDesc: "Effortlessly attract travelers who match your exact design frequency. The engine intentionally funnels high-value visitors whose philosophies interlock cleanly with yours.",
            conciergeIcon: "🧬",
            conciergeTitle: "Precision Energetic Matchmaking",
            conciergeDesc: "The premium concierge platform acts as a digital curator, matching and introducing your center directly to luxury clients whose search behaviors reflect your retreat's specific tone.",
            metrics: { vis: "+75% Lift", tar: "Value Aligned", sync: "Behavioral Sync", plac: "Premium Rotations" },
            colors: { lav: "rgba(237, 233, 254, 0.6)", pea: "rgba(255, 237, 213, 0.5)", pin: "rgba(186, 230, 253, 0.3)" },
            btnClass: "bg-gradient-to-r from-violet-500 to-pink-500 text-white shadow-md shadow-violet-500/20",
            actionLabel: "🧬 Initialize Energetic Syncing",
            actionBtnStyle: "bg-gradient-to-r from-violet-500 to-pink-500 shadow-violet-500/20"
        },
        40: {
            badge: "Absolute Market Canopy",
            label: "🔥 Top Tier Escape",
            labelStyle: "bg-pink-50 text-pink-700 border-pink-200/80 font-bold shadow-sm",
            hopeTitle: "Unshakable calendar serenity",
            hopeDesc: "Say goodbye to seasonal booking stress. Experience the true psychological relief of knowing your calendar matrices are organically recommended and filled far in advance.",
            conciergeIcon: "🎯",
            conciergeTitle: "Flagship Priority Tiering",
            conciergeDesc: "Maximum visibility saturation. Your facility becomes a premier native recommendation baseline across primary regional search streams for all premium accounts.",
            metrics: { vis: "Priority Boost", tar: "Global High-Net", sync: "Omni-Channel", plac: "Flagship Panels" },
            colors: { lav: "rgba(253, 242, 248, 0.6)", pea: "rgba(254, 215, 170, 0.5)", pin: "rgba(224, 242, 254, 0.4)" },
            btnClass: "bg-gradient-to-r from-pink-500 to-rose-500 text-white shadow-md shadow-pink-500/20",
            actionLabel: "🎯 Authorize Flagship Priority",
            actionBtnStyle: "bg-gradient-to-r from-pink-500 to-rose-500 shadow-pink-500/20"
        },
        45: {
            badge: "Sovereign Legacy Tier",
            label: "👑 Elite Handpicked Excellence",
            labelStyle: "bg-orange-50 text-orange-700 border-orange-200/80 font-bold shadow-sm animate-pulse",
            hopeTitle: "Attaining legendary legacy status",
            hopeDesc: "Anchor your life's masterwork permanently at the highest echelon of the platform canopy, fully padded by premium visibility anchors.",
            conciergeIcon: "👑",
            conciergeTitle: "Sovereign Crown Recommendation",
            conciergeDesc: "The pinnacle tier of platform endorsement. The system actively hand-curates and champions your retreat as an elite destination across all VIP corporate and private matching systems.",
            metrics: { vis: "Absolute Top", tar: "Elite VIP Only", sync: "Deep Synergistic", plac: "Crown Carousel" },
            colors: { lav: "rgba(255, 237, 213, 0.6)", pea: "rgba(254, 243, 199, 0.6)", pin: "rgba(237, 233, 254, 0.5)" },
            btnClass: "bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-md shadow-orange-500/30",
            actionLabel: "👑 Secure Sovereign Crown Canopy",
            actionBtnStyle: "bg-gradient-to-r from-orange-500 to-amber-500 shadow-orange-500/30"
        }
    };

    function matrixEngineUpdate(rawIndex) {
        const index = parseInt(rawIndex);
        const targetPct = scaleIndices[index];
        const data = transformationMatrix[targetPct];
        if (!data) return;

        // Sync Slider Coordinates
        document.getElementById('main-slider').value = index;
        document.getElementById('display-percentage').innerText = `${targetPct}%`;
        document.getElementById('tier-badge').innerText = data.badge;
        document.getElementById('customer-label').innerText = data.label;
        document.getElementById('hope-title').innerText = data.hopeTitle;
        document.getElementById('hope-desc').innerText = data.hopeDesc;
        document.getElementById('concierge-icon').innerText = data.conciergeIcon;
        document.getElementById('concierge-tier-name').innerText = data.conciergeTitle;
        document.getElementById('concierge-desc').innerHTML = `<strong id="concierge-tier-name" class="text-neutral-900 block mb-0.5 font-semibold">${data.conciergeTitle}</strong> ${data.conciergeDesc}`;

        // Sync Enriched Multi-Factor Grid Metrics
        document.getElementById('metric-visibility').innerText = data.metrics.vis;
        document.getElementById('metric-targeting').innerText = data.metrics.tar;
        document.getElementById('metric-sync').innerText = data.metrics.sync;
        document.getElementById('metric-placement').innerText = data.metrics.plac;

        // Dynamic Action Button Label & Theme Syncing
        const actionBtn = document.getElementById('action-submit-btn');
        actionBtn.innerText = data.actionLabel;
        actionBtn.className = `w-full py-4 rounded-2xl font-mono text-[10px] font-bold tracking-[0.2em] uppercase text-white transition-all duration-300 ease-out hover:scale-[1.01] active:scale-[0.99] shadow-lg ${data.actionBtnStyle}`;

        // Sync Button Classes Fluidly
        scaleIndices.forEach((pct, idx) => {
            const btn = document.getElementById(`preset-${idx}`);
            if (idx === index) {
                btn.className = `py-2 rounded-xl text-xs font-mono font-bold transition-all scale-105 ${data.btnClass}`;
            } else {
                btn.className = "py-2 bg-white text-neutral-400 border border-neutral-200/50 rounded-xl text-xs font-mono font-bold hover:bg-neutral-50/80 transition-all scale-100";
            }
        });

        // Smooth Multi-Tone Background Gradient Canvas Shift
        document.getElementById('customer-label').className = `px-3 py-1 text-[10px] tracking-wide rounded-lg transition-all duration-500 border ${data.labelStyle}`;
        document.getElementById('glow-lavender').style.backgroundColor = data.colors.lav;
        document.getElementById('glow-peach').style.backgroundColor = data.colors.pea;
        document.getElementById('glow-pink').style.backgroundColor = data.colors.pin;

        // Keep the submitted commission value in sync with the selected tier
        const hiddenInput = document.getElementById('commission-value-input');
        if (hiddenInput) hiddenInput.value = targetPct;
    }

    // Sync the whole panel to the retreat's current commission on load
    matrixEngineUpdate({{ $closestIndex }});
</script>
@endsection
