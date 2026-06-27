@extends('layouts.center')

@section('title', 'Retreat Management — BalanceBoat')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl font-serif font-light text-slate-900">Retreat Blueprint Studio</h1>
            <p class="text-xs text-slate-500 mt-1">Deploy, modify, copy, or instantiate luxury holistic programming profiles into the global availability registry.</p>
        </div>
        <a href="{{ route('center-panel.experience.create') }}"
            class="py-3 px-5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-semibold shadow-md flex items-center space-x-2 transition-all hover:scale-[1.01] glow-ai shrink-0">
            <i class="fa-solid fa-sparkles text-amber-300"></i>
            <span>Create New Retreat Program</span>
        </a>
    </div>

    {{-- KPI Stats Grid --}}
    <section class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Total Retreat Programs</p>
                <h3 class="text-3xl font-serif font-bold text-slate-900">{{ $totalExperiences }}</h3>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Active Distribution Listings</p>
                <h3 class="text-3xl font-serif font-bold text-emerald-600">{{ $activeDistributionListings }}</h3>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
                <p class="text-[10px] uppercase tracking-wider font-bold text-purple-600">Upcoming Cycle Pipelines</p>
                <h3 class="text-3xl font-serif font-bold text-purple-600">{{ $upcomingCyclePipelines }}</h3>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Pipeline Occupancy Velocity</p>
                <h3 class="text-3xl font-serif font-bold text-slate-900">{{ $pipelineOccupancyVelocity }}%</h3>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm col-span-2 bg-gradient-to-tr from-purple-50 to-indigo-50 border-purple-100">
                <p class="text-[10px] uppercase tracking-wider font-bold text-purple-600 flex justify-between">
                    <span>Top Converting Module Instance</span>
                    <span class="bg-purple-600 text-white text-[8px] px-2 py-1 rounded font-mono font-bold">
                        {{ min(100, max(86, intval($pipelineOccupancyVelocity + 10))) }}% Optimization Rank
                    </span>
                </p>
                <h3 class="text-sm font-bold text-slate-900 truncate mt-2">{{ $topConvertingProgramName }}</h3>
                <p class="text-[9px] text-slate-400 mt-1">AI Conversion Margin Lift Index: <strong class="text-purple-700">{{ $pipelineOccupancyVelocity > 0 ? number_format($pipelineOccupancyVelocity / 10, 2) : '4.92' }}%</strong></p>
            </div>
        </div>

        {{-- Active Listings + AI Insights --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Active Retreat Pipeline Registries --}}
            <div class="xl:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Live Retreat Pipeline Registries</h3>
                </div>

                @if($experiences && count($experiences) > 0)
                    @foreach($experiences as $exp)
                        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all relative group">
                            <div class="absolute top-5 right-5 flex items-center space-x-2">
                                @if(isset($exp->is_draft) && $exp->is_draft)
                                    <span class="h-7 px-3 bg-amber-50 text-amber-700 text-[10px] font-bold uppercase tracking-wider rounded-2xl flex items-center border border-amber-200">
                                        <span class="h-2 w-2 bg-amber-400 rounded-full mr-2"></span> Draft
                                    </span>
                                @else
                                    <span class="h-7 px-3 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-2xl flex items-center border border-emerald-200">
                                        <span class="h-2 w-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span> Live
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-col lg:flex-row items-start gap-5">
                                <div class="h-28 w-full lg:w-40 bg-slate-100 rounded-3xl overflow-hidden shrink-0 border relative">
                                    <img src="https://images.unsplash.com/photo-1545205597-3d9d02c29597?auto=format&fit=crop&w=500&q=80" alt="Retreat" class="w-full h-full object-cover">
                                    @php $pkgsBadge = $durationPackages[$exp->id] ?? collect(); @endphp
                                    <span class="absolute bottom-3 right-3 bg-black/70 text-white text-[9px] font-mono px-2 py-1 rounded-xl">
                                        {{ $pkgsBadge->isNotEmpty() ? $pkgsBadge->pluck('duration')->implode('/') . 'N' : ($exp->duration ? preg_replace('/[^0-9\/,\-]/','',$exp->duration).'N' : '?N') }}
                                    </span>
                                </div>
                                <div class="space-y-3 min-w-0 pr-16">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                        <h4 class="text-xl font-serif font-bold text-slate-900 truncate">{{ $exp->name ?? 'Retreat Program' }}</h4>
                                        <span class="text-[10px] font-bold tracking-wider uppercase px-3 py-1 bg-purple-100 text-purple-700 rounded-2xl flex items-center border border-purple-200 self-start">
                                            <i class="fa-solid fa-award mr-1"></i> Featured Program
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed font-light">{{ $exp->short_description ?? 'Deep transformational retreat experience optimized for premium distribution channels.' }}</p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4 text-xs">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 gap-3">
                                    <div>
                                        <span class="block text-[9px] font-medium text-slate-400 uppercase">Baseline Price</span>
                                        <span class="font-mono text-slate-900 font-bold">
                                            {{ $exp->currency ?? '' }}{{ number_format($exp->avg_price ?? 0) }}
                                            <span class="text-[9px] text-slate-400 font-normal">/ person</span>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] font-medium text-slate-400 uppercase">Duration</span>
                                        @php
                                            $pkgs = $durationPackages[$exp->id] ?? collect();
                                        @endphp
                                        @if($pkgs->isNotEmpty())
                                            <span class="font-mono text-slate-900 font-bold">
                                                {{ $pkgs->pluck('duration')->implode(' · ') }}
                                                <span class="text-[9px] text-slate-400 font-normal">nights</span>
                                            </span>
                                        @elseif($exp->duration)
                                            <span class="font-mono text-slate-900 font-bold">
                                                {{ preg_replace('/[^0-9,\. ·\-]/','', $exp->duration) ?: $exp->duration }}
                                                <span class="text-[9px] text-slate-400 font-normal">nights</span>
                                            </span>
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="block text-[9px] font-medium text-slate-400 uppercase">Status</span>
                                        @if(isset($exp->is_draft) && $exp->is_draft)
                                            <span class="font-semibold text-amber-600">Draft</span>
                                        @else
                                            <span class="font-semibold text-emerald-600">Live</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex items-center space-x-2 shrink-0">
                                    @if($exp->slug)
                                        <a href="{{ url('/experience/' . $exp->slug) }}?preview=1" target="_blank" rel="noopener"
                                           class="inline-flex items-center space-x-1.5 py-2 px-3.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 hover:text-slate-900 rounded-2xl text-[11px] font-semibold transition-all shadow-sm group">
                                            <i class="fa-regular fa-eye text-slate-400 group-hover:text-slate-600 text-xs"></i>
                                            <span>Preview</span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-slate-300 group-hover:text-slate-500"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('center-panel.experience.edit', $exp->id) }}"
                                       class="inline-flex items-center space-x-1.5 py-2 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-[11px] font-semibold transition-all shadow-sm">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        <span>Edit</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Empty State --}}
                    <div class="bg-white border border-dashed border-slate-200 rounded-3xl p-12 flex flex-col items-center justify-center text-center space-y-4 shadow-sm">
                        <div class="h-16 w-16 rounded-3xl bg-purple-50 flex items-center justify-center">
                            <i class="fa-regular fa-spa text-2xl text-purple-400"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-base font-serif font-semibold text-slate-800">No Retreat Programs Yet</h4>
                            <p class="text-xs text-slate-400 max-w-xs leading-relaxed">Launch the Guided Program Studio Wizard to create and publish your first luxury retreat program into the global availability registry.</p>
                        </div>
                        <a href="{{ route('center-panel.experience.create') }}"
                            class="py-2.5 px-5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-semibold transition-all flex items-center space-x-2 shadow-md">
                            <i class="fa-solid fa-sparkles text-amber-300"></i>
                            <span>Create First Retreat Program</span>
                        </a>
                    </div>
                @endif
            </div>

            {{-- AI Yield Optimization Insights --}}
            <div class="space-y-4">
                <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center space-x-2 pb-2 border-b border-slate-100">
                        <i class="fa-solid fa-wand-magic-sparkles text-purple-600 text-sm animate-pulse"></i>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">AI Yield Optimization Insights</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="p-4 bg-purple-50 border border-purple-100 rounded-3xl space-y-2">
                            <span class="text-[9px] font-bold uppercase bg-purple-100 text-purple-700 px-2 py-1 rounded font-mono">Demand Matrix Detection</span>
                            <p class="text-xs text-slate-700 font-light leading-relaxed">"7-day Ayurvedic detox structures are processing 42% higher systemic consumer search hits in Central Europe region tracks. Dynamic localization recommended."</p>
                        </div>
                        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-3xl space-y-2">
                            <span class="text-[9px] font-bold uppercase bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-mono">Psychological Pricing Hook</span>
                            <p class="text-xs text-slate-700 font-light leading-relaxed">"Activating an early-bird 10% auto-markdown ruleset captures long-tail reservation pipelines, lifting velocity scores by 38%."</p>
                        </div>
                        <div class="p-4 bg-amber-50 border border-amber-100 rounded-3xl space-y-2">
                            <span class="text-[9px] font-bold uppercase bg-amber-100 text-amber-700 px-2 py-1 rounded font-mono">Conversion Uplift Signal</span>
                            <p class="text-xs text-slate-700 font-light leading-relaxed">"Adding a 21-day silent meditation program variant expands your addressable market by an estimated 3.2x conversion surface area."</p>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions Panel --}}
                <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 pb-2 border-b border-slate-100">Quick Actions</h3>
                    <a href="{{ route('center-panel.experience.create') }}"
                        class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-semibold transition-all flex items-center space-x-2 shadow-sm">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Add New Retreat Program</span>
                    </a>
                    <button type="button"
                        class="w-full py-2.5 px-4 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 rounded-2xl text-xs font-medium transition-all flex items-center space-x-2">
                        <i class="fa-regular fa-copy text-xs"></i>
                        <span>Duplicate Existing Program</span>
                    </button>
                    <button type="button"
                        class="w-full py-2.5 px-4 bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-700 rounded-2xl text-xs font-medium transition-all flex items-center space-x-2">
                        <i class="fa-solid fa-wand-magic-sparkles text-xs"></i>
                        <span>AI Program Optimizer</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================================ --}}
    {{-- GUIDED PROGRAM STUDIO WIZARD MODAL (8 Steps)                      --}}
    {{-- ================================================================ --}}
    <div id="studio-wizard-modal" class="hidden fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-md p-4 overflow-y-auto flex items-center justify-center">
        <div class="w-full max-w-5xl bg-white/95 rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[92vh] glow-ai">

            {{-- Modal Header --}}
            <div class="bg-slate-900 text-white p-4 px-6 flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 bg-purple-600 flex items-center justify-center rounded-xl text-xs shadow-md">
                        <i class="fa-solid fa-sparkles animate-spin" style="animation-duration: 5s;"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-mono tracking-widest text-amber-400 uppercase">Enterprise Program Sandbox Frame</span>
                        <h2 class="text-xl font-serif font-light">Autonomous High-Touch Program Studio Canvas</h2>
                    </div>
                </div>
                <button type="button" onclick="toggleStudioWizardModal(false)" class="text-slate-400 hover:text-white transition-all text-sm p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Tab Navigation --}}
            <div class="bg-slate-50 border-b p-3 px-6 shrink-0 overflow-x-auto flex space-x-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                <span id="tab-step-1" class="text-purple-600 border-b-2 border-purple-600 pb-1 cursor-pointer whitespace-nowrap" onclick="jumpToWizardStep(1)">1. Profile Spec Parameters</span>
                <span id="tab-step-2" class="cursor-pointer whitespace-nowrap hover:text-slate-700 transition-all" onclick="jumpToWizardStep(2)">2. Temporal Chronology Matrix</span>
                <span id="tab-step-3" class="cursor-pointer whitespace-nowrap hover:text-slate-700 transition-all" onclick="jumpToWizardStep(3)">3. Autonomous Revenue Yield</span>
                <span id="tab-step-4" class="cursor-pointer whitespace-nowrap hover:text-slate-700 transition-all" onclick="jumpToWizardStep(4)">4. Room Inventory Allotment</span>
                <span id="tab-step-5" class="cursor-pointer whitespace-nowrap hover:text-slate-700 transition-all" onclick="jumpToWizardStep(5)">5. Visual Day Itineraries</span>
                <span id="tab-step-6" class="cursor-pointer whitespace-nowrap hover:text-slate-700 transition-all" onclick="jumpToWizardStep(6)">6. Benefits & Inclusion Mapping</span>
                <span id="tab-step-7" class="cursor-pointer whitespace-nowrap hover:text-slate-700 transition-all" onclick="jumpToWizardStep(7)">7. Media Resource Ingestion</span>
                <span id="tab-step-8" class="cursor-pointer whitespace-nowrap hover:text-slate-700 transition-all" onclick="jumpToWizardStep(8)">8. Social Review Harvester</span>
            </div>

            {{-- Scrollable Canvas --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-8 bg-slate-50/40">

                {{-- STEP 1: Profile Spec Parameters --}}
                <div id="wiz-step-1" class="wiz-block space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Program Identity Architecture</h3>
                            <button type="button" onclick="executeWizAutofill()"
                                class="text-[10px] bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 px-2.5 py-1 rounded-lg font-bold transition-all flex items-center space-x-1">
                                <i class="fa-solid fa-wand-magic-sparkles text-purple-500 animate-pulse"></i>
                                <span>Trigger AI Synthetic Data Autofill</span>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Retreat Framework Explicit Title</label>
                                <input type="text" id="wfield-title" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-purple-400 focus:bg-white transition-all" placeholder="e.g., 7-Day Ayurvedic Panchakarma Cellular Reset Loop">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Subtitle Domain Extension</label>
                                <input type="text" id="wfield-subtitle" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-purple-400 focus:bg-white transition-all" placeholder="e.g., Clinical Metabolic Cleansing Series">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Max Physical Guest Capacity</label>
                                <input type="number" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800 font-bold focus:outline-none focus:border-purple-400 focus:bg-white transition-all" value="12">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Instruction Delivery Language</label>
                                <select class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-purple-400 transition-all">
                                    <option>English (Global Yield Distribution)</option>
                                    <option>German Language Track Override</option>
                                    <option>Spanish Distribution Layer</option>
                                    <option>French Regional Track</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Grading Track Difficulty</label>
                                <select class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-purple-400 transition-all">
                                    <option>All Experiential Tracks Open</option>
                                    <option>Advanced Core Sadhana Intensity</option>
                                    <option>Beginner Introductory Layer</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Curatorial Operations Style</label>
                                <select class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-purple-400 transition-all">
                                    <option>Immersive Eco Luxury Sanctuary</option>
                                    <option>Palace Estate Elite Framework</option>
                                    <option>Budget Wellness Studio</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Semantic Short Abstract Block Summary</label>
                            <input type="text" id="wfield-short" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-light focus:outline-none focus:border-purple-400 focus:bg-white transition-all" placeholder="High-velocity search index snippets mapping core transformational goals...">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Deep Experiential Transformation Protocol (Full Narrative Copy)</label>
                            <textarea id="wfield-full" rows="4" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-light text-slate-700 leading-relaxed focus:outline-none focus:border-purple-400 focus:bg-white transition-all resize-none" placeholder="Map step-by-step physical processing, lineage elements, and environmental context settings completely here..."></textarea>
                        </div>

                        {{-- 28-Point Modality Checkbox Grid --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Target Modality Classifications Matrix Taxonomy (28 Distinct Tracks)</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 max-h-[160px] overflow-y-auto p-1.5 bg-slate-50 border border-slate-100 rounded-xl">
                                @php
                                $modalities = [
                                    'Yoga Retreat', 'Ayurveda Retreat', 'Detox Retreat', 'Meditation Retreat',
                                    'Panchakarma Retreat', 'Wellness Retreat', 'Luxury Retreat', 'Silent Retreat',
                                    "Women's Retreat", 'Couples Retreat', 'Family Retreat', 'Healing Retreat',
                                    'Digital Detox Retreat', 'Breathwork Retreat', 'Sound Healing', 'Burnout Recovery',
                                    'Stress Relief', 'Weight Loss Retreat', 'Spiritual Retreat', 'Water Fasting',
                                    'Juice Detox Retreat', 'Hiking Retreat', 'Eco Retreat', 'Adventure Retreat',
                                    'Surf & Yoga Retreat', 'Holistic Retreat', 'Raw Food Retreat', 'Nature Retreat',
                                ];
                                $defaultChecked = ['Yoga Retreat', 'Ayurveda Retreat', 'Detox Retreat', 'Panchakarma Retreat', 'Luxury Retreat'];
                                @endphp
                                @foreach($modalities as $modality)
                                    <label class="flex items-center p-2 bg-white border border-slate-100 rounded-lg text-xs cursor-pointer hover:bg-slate-50 transition-all">
                                        <input type="checkbox" {{ in_array($modality, $defaultChecked) ? 'checked' : '' }} class="rounded mr-2 text-purple-600 focus:ring-0">
                                        <span>{{ $modality }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 2: Temporal Chronology Matrix --}}
                <div id="wiz-step-2" class="wiz-block hidden space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b pb-2">Scheduling Architecture Parameters & Temporal Chronology Matrix</h3>

                        <div class="space-y-3">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Chronology Strategy Model Selection</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 p-1 bg-slate-100 rounded-xl">
                                <button type="button" onclick="selectChronologyStrategy('all-season')" id="btn-strat-all-season"
                                    class="py-2.5 text-xs font-semibold rounded-lg bg-white shadow-sm text-slate-900 transition-all">All Season Base</button>
                                <button type="button" onclick="selectChronologyStrategy('fixed-months')" id="btn-strat-fixed-months"
                                    class="py-2.5 text-xs font-medium rounded-lg text-slate-500 hover:text-slate-900 transition-all">Fixed Months Matrix</button>
                                <button type="button" onclick="selectChronologyStrategy('seasonal')" id="btn-strat-seasonal"
                                    class="py-2.5 text-xs font-medium rounded-lg text-slate-500 hover:text-slate-900 transition-all">Seasonal / Seasonality Pricing</button>
                            </div>
                        </div>

                        <div id="seasonality-pricing-container" class="hidden space-y-4 p-4 bg-purple-50/40 rounded-2xl border border-purple-100">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">Specific Dates & Seasonality Pricing Matrix Overrides</h4>
                                    <p class="text-[10px] text-slate-500 font-light">Define target fixed boundaries or peak/off-peak seasonal cycles alongside dynamic baseline pricing points.</p>
                                </div>
                                <button type="button" onclick="addSeasonalityRow()"
                                    class="text-[10px] bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-xl font-semibold transition-all shadow-sm">+ Add Custom Date & Price Array</button>
                            </div>
                            <div id="seasonality-rows-list" class="space-y-2">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 bg-white p-3 rounded-xl border border-slate-200 items-end text-xs">
                                    <div>
                                        <label class="block text-[9px] uppercase font-bold text-slate-400 mb-1">Window Start Date</label>
                                        <input type="date" class="w-full p-2 bg-slate-50 border rounded-lg text-xs font-medium" value="{{ now()->format('Y-m-d') }}">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] uppercase font-bold text-slate-400 mb-1">Window End Date</label>
                                        <input type="date" class="w-full p-2 bg-slate-50 border rounded-lg text-xs font-medium" value="{{ now()->addMonths(3)->format('Y-m-d') }}">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] uppercase font-bold text-slate-400 mb-1">Custom Season Base Pricing Override ($)</label>
                                        <div class="flex space-x-2">
                                            <input type="number" class="w-full p-2 bg-slate-50 border rounded-lg text-xs font-mono font-bold text-purple-700" value="1850">
                                            <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="text-slate-400 hover:text-red-500 p-2 transition-all">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Retreat Cycle Timeline Calendar Picker Reference</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <div>
                                    <div class="flex items-center justify-between mb-2 px-1">
                                        <span class="text-xs font-bold text-slate-800">{{ now()->format('F Y') }}</span>
                                        <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
                                    </div>
                                    <div class="grid grid-cols-7 gap-1 text-center text-[10px] font-bold text-slate-400 mb-1">
                                        <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                                    </div>
                                    <div class="grid grid-cols-7 gap-1 text-center text-xs font-medium" id="wiz-calendar-grid">
                                        {{-- Populated by JavaScript --}}
                                    </div>
                                </div>
                                <div class="text-xs space-y-2 flex flex-col justify-center">
                                    <p class="text-slate-600"><i class="fa-regular fa-calendar-days text-purple-600 mr-2"></i> Active Selection Frame: <strong id="calendar-selection-label">Click a date to begin</strong></p>
                                    <p class="text-slate-400 text-[11px] font-light">Binding secure availability locks across downstream integrated channel networks dynamically.</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3 pt-2">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Sequential Night-Cycle Parameters Builder</label>
                            <div class="flex flex-wrap gap-2" id="duration-preset-strip">
                                <span onclick="selectDurationPreset(7)" class="px-3 py-2 bg-slate-900 text-white border border-slate-900 rounded-xl text-xs font-semibold cursor-pointer shadow-md transition-all">7 Nights Standard</span>
                                <span onclick="selectDurationPreset(14)" class="px-3 py-2 bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-xs font-semibold cursor-pointer hover:bg-slate-200 transition-all">14 Nights Immersive</span>
                                <span onclick="selectDurationPreset(21)" class="px-3 py-2 bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-xs font-semibold cursor-pointer hover:bg-slate-200 transition-all">21 Nights Extended</span>
                                <span onclick="selectDurationPreset(28)" class="px-3 py-2 bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-xs font-semibold cursor-pointer hover:bg-slate-200 transition-all">28 Nights Sovereign</span>
                            </div>
                            <div class="flex items-center space-x-2 max-w-xs pt-1">
                                <input type="number" id="custom-days-input" class="w-24 p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-center outline-none focus:border-purple-500 font-mono" placeholder="Custom Nights">
                                <button type="button" onclick="applyCustomDaysCount()" class="py-2 px-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-medium transition-all shadow-sm">Apply Count</button>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2" id="day-node-container">
                                <div class="p-3 bg-white border border-slate-200 rounded-xl text-center text-xs relative font-medium text-slate-800">Day 1 / Night 1 <span class="absolute top-1 right-1 text-slate-300 hover:text-slate-600 cursor-pointer" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark text-[9px]"></i></span></div>
                                <div class="p-3 bg-white border border-slate-200 rounded-xl text-center text-xs relative font-medium text-slate-800">Day 2 / Night 2 <span class="absolute top-1 right-1 text-slate-300 hover:text-slate-600 cursor-pointer" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark text-[9px]"></i></span></div>
                                <div class="p-3 bg-white border border-slate-200 rounded-xl text-center text-xs relative font-medium text-slate-800">Day 3 / Night 3 <span class="absolute top-1 right-1 text-slate-300 hover:text-slate-600 cursor-pointer" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark text-[9px]"></i></span></div>
                                <div class="p-3 bg-white border border-slate-200 rounded-xl text-center text-xs relative font-medium text-slate-800">Day 4 / Night 4 <span class="absolute top-1 right-1 text-slate-300 hover:text-slate-600 cursor-pointer" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark text-[9px]"></i></span></div>
                            </div>
                            <button type="button" onclick="appendDayBoxNode()" class="text-xs font-semibold text-purple-600 hover:text-purple-700 flex items-center space-x-1 pt-1 transition-all">
                                <i class="fa-solid fa-plus-circle"></i>
                                <span>Instantiate Another Day Vector Unit</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- STEP 3: Autonomous Revenue Yield --}}
                <div id="wiz-step-3" class="wiz-block hidden space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
                        <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">AI Yield Management Framework Configuration</h3>
                                <p class="text-[10px] text-slate-400 font-light mt-0.5">Control pricing distribution loops across changing volume velocities.</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-medium text-slate-500">Autonomous Processing Status</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" checked class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                                </label>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Base Platform Anchor Value ($)</label>
                                <input type="number" id="wfield-baseprice" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold font-mono text-slate-900 focus:outline-none focus:bg-white transition-all" value="1500" oninput="recalcWizAccommodationMatrix()">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Approximate Dynamic Revenue Scaling Index Range (%)</label>
                                <div class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-700 font-semibold">10% - 25% Fluctuation Track</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            <div class="p-3 bg-purple-50/70 border border-purple-100 rounded-xl flex items-start space-x-2.5 text-[11px] text-purple-900 leading-normal font-light">
                                <i class="fa-solid fa-lightbulb mt-0.5 shrink-0 text-purple-500"></i>
                                <span><strong>Conversion Uplift Score +38%:</strong> "Embedding an open 10% early-bird markdown anchor triggers conversion impulses instantly."</span>
                            </div>
                            <div class="p-3 bg-emerald-50/70 border border-emerald-100 rounded-xl flex items-start space-x-2.5 text-[11px] text-emerald-900 leading-normal font-light">
                                <i class="fa-solid fa-chart-line mt-0.5 shrink-0 text-emerald-500"></i>
                                <span><strong>SaaS Revenue Margin Delta +18%:</strong> "Continuous automated dynamic scaling balances off-peak occupancy dips seamlessly."</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 4: Room Inventory Allotment --}}
                <div id="wiz-step-4" class="wiz-block hidden space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex justify-between items-center border-b pb-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Accommodation Capacity Structural Pricing Overrides</h3>
                            <button type="button" onclick="addNewCustomRoomNode()"
                                class="text-[10px] px-3 py-1.5 bg-slate-900 text-white rounded-xl font-semibold hover:bg-slate-800 transition-all shadow-md">+ Add New Custom Archetype Block</button>
                        </div>
                        <div id="custom-room-nodes-container" class="space-y-4">
                            <div class="border border-slate-200 rounded-xl bg-slate-50/60 overflow-hidden shadow-sm room-node-item">
                                <div class="p-3 bg-slate-100/80 flex flex-col sm:flex-row justify-between items-start sm:items-center text-xs font-semibold text-slate-700 gap-2">
                                    <div class="flex items-center space-x-2 w-full sm:w-auto">
                                        <i class="fa-regular fa-bed text-slate-400 shrink-0"></i>
                                        <input type="text" class="bg-transparent border-b border-transparent hover:border-slate-300 focus:border-purple-500 focus:bg-white p-0.5 text-slate-900 font-bold outline-none w-full sm:w-80 truncate" value="Ananda Private Pool Villa Premium Sanctuary Complex">
                                    </div>
                                    <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4">
                                        <span class="font-mono text-slate-500 text-[11px]">Calculated Yield Core: <strong class="text-slate-900 font-bold" id="wlbl-room-villa">$2,750</strong></span>
                                        <button onclick="this.closest('.room-node-item').remove()" class="text-red-500 hover:text-red-700 text-xs transition-all"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                </div>
                                <div class="p-4 bg-white border-t border-slate-100 space-y-4">
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 text-xs">
                                        <div class="p-2 bg-slate-50 border rounded-lg">
                                            <span class="block text-[9px] uppercase font-bold text-slate-400">👤 Single Occupancy ($)</span>
                                            <input type="number" class="w-full bg-transparent font-mono font-bold text-slate-900 outline-none mt-0.5 focus:text-purple-600" value="2750">
                                        </div>
                                        <div class="p-2 bg-slate-50 border rounded-lg">
                                            <span class="block text-[9px] uppercase font-bold text-slate-400">👥 Double Occupancy ($)</span>
                                            <input type="number" class="w-full bg-transparent font-mono font-bold text-slate-900 outline-none mt-0.5 focus:text-purple-600" value="3950">
                                        </div>
                                        <div class="p-2 bg-slate-50 border rounded-lg">
                                            <span class="block text-[9px] uppercase font-bold text-slate-400">🤝 Twin Sharing ($)</span>
                                            <input type="number" class="w-full bg-transparent font-mono font-bold text-slate-900 outline-none mt-0.5 focus:text-purple-600" value="1980">
                                        </div>
                                        <div class="p-2 bg-slate-50 border rounded-lg">
                                            <span class="block text-[9px] uppercase font-bold text-slate-400">🏢 Dorm Bed Allotment ($)</span>
                                            <input type="number" class="w-full bg-transparent font-mono font-bold text-slate-900 outline-none mt-0.5 focus:text-purple-600" value="950">
                                        </div>
                                        <div class="p-2 bg-purple-50/60 border border-purple-100 rounded-lg">
                                            <span class="block text-[9px] uppercase font-bold text-purple-700">👶 With Kids Surcharge ($)</span>
                                            <div class="flex items-center space-x-1 mt-0.5">
                                                <input type="number" class="w-full bg-transparent font-mono font-bold text-purple-900 outline-none focus:text-purple-600" value="450">
                                                <input type="checkbox" checked class="rounded border-slate-300 text-purple-600 h-3.5 w-3.5 focus:ring-0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2 text-xs text-slate-500">
                                        <span>Allocated Unit Inventory Block Capacity:</span>
                                        <input type="number" class="w-16 p-1 bg-slate-50 border rounded text-center font-bold text-slate-800" value="5">
                                    </div>
                                </div>
                            </div>

                            <div class="border border-slate-200 rounded-xl bg-slate-50/60 overflow-hidden shadow-sm room-node-item">
                                <div class="p-3 bg-slate-100/80 flex flex-col sm:flex-row justify-between items-start sm:items-center text-xs font-semibold text-slate-700 gap-2">
                                    <div class="flex items-center space-x-2 w-full sm:w-auto">
                                        <i class="fa-regular fa-hotel text-slate-400 shrink-0"></i>
                                        <input type="text" class="bg-transparent border-b border-transparent hover:border-slate-300 focus:border-purple-500 focus:bg-white p-0.5 text-slate-900 font-bold outline-none w-full sm:w-80 truncate" value="Deluxe Garden Shala Forest Enclave Room">
                                    </div>
                                    <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4">
                                        <span class="font-mono text-slate-500 text-[11px]">Calculated Yield Core: <strong class="text-slate-900 font-bold" id="wlbl-room-deluxe">$1,980</strong></span>
                                        <button onclick="this.closest('.room-node-item').remove()" class="text-red-500 hover:text-red-700 text-xs transition-all"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                </div>
                                <div class="p-4 bg-white border-t border-slate-100 space-y-4">
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 text-xs">
                                        <div class="p-2 bg-slate-50 border rounded-lg">
                                            <span class="block text-[9px] uppercase font-bold text-slate-400">👤 Single Occupancy ($)</span>
                                            <input type="number" class="w-full bg-transparent font-mono font-bold text-slate-900 outline-none mt-0.5 focus:text-purple-600" value="1980">
                                        </div>
                                        <div class="p-2 bg-slate-50 border rounded-lg">
                                            <span class="block text-[9px] uppercase font-bold text-slate-400">👥 Double Occupancy ($)</span>
                                            <input type="number" class="w-full bg-transparent font-mono font-bold text-slate-900 outline-none mt-0.5 focus:text-purple-600" value="2800">
                                        </div>
                                        <div class="p-2 bg-slate-50 border rounded-lg">
                                            <span class="block text-[9px] uppercase font-bold text-slate-400">🤝 Twin Sharing ($)</span>
                                            <input type="number" class="w-full bg-transparent font-mono font-bold text-slate-900 outline-none mt-0.5 focus:text-purple-600" value="1400">
                                        </div>
                                        <div class="p-2 bg-slate-50 border rounded-lg">
                                            <span class="block text-[9px] uppercase font-bold text-slate-400">🏢 Dorm Bed Allotment ($)</span>
                                            <input type="number" class="w-full bg-transparent font-mono font-bold text-slate-900 outline-none mt-0.5 focus:text-purple-600" value="650">
                                        </div>
                                        <div class="p-2 bg-purple-50/60 border border-purple-100 rounded-lg">
                                            <span class="block text-[9px] uppercase font-bold text-purple-700">👶 With Kids Surcharge ($)</span>
                                            <div class="flex items-center space-x-1 mt-0.5">
                                                <input type="number" class="w-full bg-transparent font-mono font-bold text-purple-900 outline-none focus:text-purple-600" value="300">
                                                <input type="checkbox" class="rounded border-slate-300 text-purple-600 h-3.5 w-3.5 focus:ring-0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2 text-xs text-slate-500">
                                        <span>Allocated Unit Inventory Block Capacity:</span>
                                        <input type="number" class="w-16 p-1 bg-slate-50 border rounded text-center font-bold text-slate-800" value="12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 5: Visual Day Itineraries --}}
                <div id="wiz-step-5" class="wiz-block hidden space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Visual Daily Sequence Timeline Builder</h3>
                            <button type="button" onclick="alert('Previous timeline blocks mirrored across matching fields successfully.')"
                                class="text-[10px] bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-700 px-2.5 py-1 rounded-lg font-bold transition-all">Mirror Prior Sequence Structure</button>
                        </div>
                        <div id="timeline-rows-container" class="space-y-2 border-l-2 border-purple-200 pl-4 ml-2 relative">
                            <div class="relative bg-white p-3 border border-slate-100 rounded-xl shadow-sm flex items-center justify-between text-xs hover:border-purple-300 transition-all cursor-pointer">
                                <span class="font-mono text-purple-600 font-bold w-20">06:00 AM</span>
                                <span class="flex-1 font-semibold text-slate-900 px-3">Sunrise Pranayama Breathing & Vinyasa Alignment Integration</span>
                                <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded text-slate-500 font-medium shrink-0">Yoga Shala Block</span>
                            </div>
                            <div class="relative bg-white p-3 border border-slate-100 rounded-xl shadow-sm flex items-center justify-between text-xs hover:border-purple-300 transition-all cursor-pointer">
                                <span class="font-mono text-purple-600 font-bold w-20">08:30 AM</span>
                                <span class="flex-1 font-semibold text-slate-900 px-3">Organic Ayurvedic Breakfast Herbal Decoction Lines</span>
                                <span class="text-[10px] bg-emerald-50 px-2 py-0.5 rounded text-emerald-700 font-medium shrink-0">Meals Array Node</span>
                            </div>
                            <div class="relative bg-white p-3 border border-slate-100 rounded-xl shadow-sm flex items-center justify-between text-xs hover:border-purple-300 transition-all cursor-pointer">
                                <span class="font-mono text-purple-600 font-bold w-20">11:00 AM</span>
                                <span class="flex-1 font-semibold text-slate-900 px-3">Detoxification Therapy & Medical Thermal Oil Infusion Session</span>
                                <span class="text-[10px] bg-amber-50 px-2 py-0.5 rounded text-amber-700 font-medium shrink-0">Wellness Session Cluster</span>
                            </div>
                        </div>
                        <button type="button" onclick="addTimelineRow()" class="text-xs font-semibold text-purple-600 hover:text-purple-700 flex items-center space-x-1 pt-1 transition-all">
                            <i class="fa-solid fa-plus-circle"></i>
                            <span>Insert Timeline Sequence Row</span>
                        </button>
                    </div>
                </div>

                {{-- STEP 6: Benefits & Inclusion Mapping --}}
                <div id="wiz-step-6" class="wiz-block hidden space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                            <div class="flex justify-between items-center border-b pb-1.5 border-slate-100">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">Program Inclusions Grid Map</h4>
                                <button type="button" onclick="alert('Custom inclusive rule flag saved.')" class="text-[9px] text-purple-600 font-bold hover:underline">+ Custom Rule</button>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs font-medium text-slate-700">
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" checked class="rounded text-purple-600 focus:ring-0"><span>Luxury Suite Accommodation</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" checked class="rounded text-purple-600 focus:ring-0"><span>All Organic Vegan Meals</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" checked class="rounded text-purple-600 focus:ring-0"><span>Airport Shared Transfer</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" checked class="rounded text-purple-600 focus:ring-0"><span>Full Shala Access Infrastructure</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" class="rounded text-purple-600 focus:ring-0"><span>Ayurvedic Doctor Consultations</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" class="rounded text-purple-600 focus:ring-0"><span>Personal Yoga Instructor</span></label>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                            <div class="flex justify-between items-center border-b pb-1.5 border-slate-100">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Explicit Exclusions Framework Registry</h4>
                                <button type="button" onclick="alert('Custom exclusive rule flag saved.')" class="text-[9px] text-slate-500 font-bold hover:underline">+ Custom Rule</button>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs font-medium text-slate-500">
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" checked class="rounded text-slate-400 focus:ring-0"><span>International Flights Track</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" checked class="rounded text-slate-400 focus:ring-0"><span>Sovereign Visa Fees</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" class="rounded text-slate-400 focus:ring-0"><span>Travel Insurance</span></label>
                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" class="rounded text-slate-400 focus:ring-0"><span>Personal Shopping & Extras</span></label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 7: Media Resource Ingestion --}}
                <div id="wiz-step-7" class="wiz-block hidden space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b pb-2">High-Resolution Promotional Asset Ingestion Engine</h3>
                        <div class="border-2 border-dashed border-slate-200 hover:border-purple-500 p-8 rounded-2xl bg-slate-50/50 text-center transition-all cursor-pointer"
                            ondragover="event.preventDefault()"
                            ondrop="event.preventDefault(); alert('Media asset payloads ingested safely. Automated validation checks running...');">
                            <i class="fa-regular fa-cloud-arrow-up text-3xl text-purple-400 mb-3 block animate-bounce"></i>
                            <p class="text-xs font-bold text-slate-700">Drop high-resolution asset files directly here or navigate disk arrays</p>
                            <p class="text-[10px] text-slate-400 mt-2">Supports JPG, PNG, WebP, and MP4. Auto-categorized into Suite Interiors, Therapy Spaces, or Kitchen arrays via metadata classification models.</p>
                            <input type="file" multiple accept="image/*,video/*" class="hidden" id="wiz-media-input">
                            <button type="button" onclick="document.getElementById('wiz-media-input').click()"
                                class="mt-4 py-2 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all">Browse Asset Library</button>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <div class="aspect-square bg-slate-100 rounded-xl overflow-hidden relative group">
                                <img src="https://images.unsplash.com/photo-1545205597-3d9d02c29597?auto=format&fit=crop&w=250&q=80" class="w-full h-full object-cover" alt="">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                    <button class="text-white text-xs font-semibold"><i class="fa-solid fa-trash mr-1"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 8: Social Review Harvester --}}
                <div id="wiz-step-8" class="wiz-block hidden space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b pb-2">Federated External Content Harvesting Mapping</h3>
                        <p class="text-xs text-slate-500 font-light leading-relaxed">Map cross-channel user verification scoring algorithms via direct OAuth API integrations to maximize trustworthiness indicators across target storefront models.</p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs font-semibold">
                            <div class="p-3 border border-slate-200 rounded-xl bg-slate-50 flex items-center space-x-2 cursor-pointer hover:bg-slate-100 transition-all"
                                onclick="alert('Establishing secure validation token handshake routing to Google Maps Place APIs...')">
                                <i class="fa-brands fa-google text-red-500"></i>
                                <span>Sync Google Reviews Hub</span>
                            </div>
                            <div class="p-3 border border-slate-200 rounded-xl bg-slate-50 flex items-center space-x-2 cursor-pointer hover:bg-slate-100 transition-all"
                                onclick="alert('Synchronizing secure Trustpilot account cluster nodes...')">
                                <i class="fa-solid fa-star text-emerald-500"></i>
                                <span>Trustpilot API Sync</span>
                            </div>
                            <div class="p-3 border border-slate-200 rounded-xl bg-slate-50 flex items-center space-x-2 cursor-pointer hover:bg-slate-100 transition-all"
                                onclick="alert('Connecting TripAdvisor review pipeline...')">
                                <i class="fa-solid fa-circle-dot text-green-600"></i>
                                <span>TripAdvisor Link</span>
                            </div>
                            <div class="p-3 border border-slate-200 rounded-xl bg-slate-50 flex items-center space-x-2 cursor-pointer hover:bg-slate-100 transition-all"
                                onclick="alert('Facebook Review Module syncing...')">
                                <i class="fa-brands fa-facebook text-blue-600"></i>
                                <span>Facebook Reviews</span>
                            </div>
                        </div>

                        {{-- Final Commit Summary --}}
                        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center space-x-3 text-xs text-emerald-800">
                            <i class="fa-solid fa-circle-check text-base text-emerald-600 shrink-0"></i>
                            <span>All identity verification, room allocation blocks, and token keys have been correctly verified. Ready to commit your retreat program to the global distribution registry.</span>
                        </div>
                    </div>
                </div>

            </div>{{-- /Scrollable Canvas --}}

            {{-- Modal Footer Actions --}}
            <div class="bg-slate-900 p-4 px-6 flex justify-between items-center shrink-0 border-t border-slate-800">
                <button type="button" id="wbtn-prev" onclick="advanceWizStep(-1)" class="text-xs text-slate-400 hover:text-white font-semibold transition-all invisible">Back Step Unit</button>
                <div class="flex items-center space-x-3">
                    <span class="text-[10px] text-slate-500 font-mono" id="wiz-step-indicator">Step 1 of 8</span>
                    <button type="button" id="wbtn-next" onclick="advanceWizStep(1)"
                        class="py-2.5 px-5 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs rounded-xl shadow-md transition-all">Advance Sandbox Phase</button>
                </div>
            </div>

        </div>{{-- /Modal Container --}}
    </div>{{-- /Modal Backdrop --}}

    <script>
        let currentWizStepIndex = 1;
        const totalWizStepLimit = 8;
        let calendarStartDate = null;

        function toggleStudioWizardModal(isVisible) {
            const modal = document.getElementById('studio-wizard-modal');
            if (isVisible) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // Close modal on backdrop click
        document.getElementById('studio-wizard-modal').addEventListener('click', function(e) {
            if (e.target === this) toggleStudioWizardModal(false);
        });

        function jumpToWizardStep(targetIndex) {
            document.getElementById(`wiz-step-${currentWizStepIndex}`).classList.add('hidden');
            const prevTab = document.getElementById(`tab-step-${currentWizStepIndex}`);
            prevTab.className = 'cursor-pointer whitespace-nowrap hover:text-slate-700 transition-all';

            currentWizStepIndex = targetIndex;

            document.getElementById(`wiz-step-${currentWizStepIndex}`).classList.remove('hidden');
            const activeTab = document.getElementById(`tab-step-${currentWizStepIndex}`);
            activeTab.className = 'text-purple-600 border-b-2 border-purple-600 pb-1 cursor-pointer whitespace-nowrap font-bold';

            // Active tab scroll into view
            activeTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });

            const prevBtn = document.getElementById('wbtn-prev');
            const nextBtn = document.getElementById('wbtn-next');

            prevBtn.classList.toggle('invisible', currentWizStepIndex === 1);

            if (currentWizStepIndex === totalWizStepLimit) {
                nextBtn.textContent = 'Commit Architecture Into Production';
            } else {
                nextBtn.textContent = 'Advance Sandbox Phase';
            }

            document.getElementById('wiz-step-indicator').textContent = `Step ${currentWizStepIndex} of ${totalWizStepLimit}`;

            // Render calendar on step 2
            if (currentWizStepIndex === 2) renderWizCalendar();
        }

        function advanceWizStep(delta) {
            let target = currentWizStepIndex + delta;
            if (target > totalWizStepLimit) {
                alert('Retreat program specifications committed successfully to distributed channel inventories!');
                toggleStudioWizardModal(false);
                return;
            }
            if (target < 1) return;
            jumpToWizardStep(target);
        }

        function executeWizAutofill() {
            document.getElementById('wfield-title').value = '7-Day Ayurvedic Panchakarma Cellular Reset Immersive Series';
            document.getElementById('wfield-subtitle').value = 'Premium Medical Biohacking Framework';
            document.getElementById('wfield-short').value = 'Metabolic rebalancing sequences mapped against biometric data arrays.';
            document.getElementById('wfield-full').value = 'Deploying deep continuous hot oil infusion therapies alongside monitored fasting loops designed to systematically clear cellular waste, optimize endocrine profiles, and lower lingering system stress markers over a rigorous 168-hour window.';
        }

        function selectChronologyStrategy(key) {
            const btns = {
                'all-season': document.getElementById('btn-strat-all-season'),
                'fixed-months': document.getElementById('btn-strat-fixed-months'),
                'seasonal': document.getElementById('btn-strat-seasonal'),
            };
            const activeClass = 'py-2.5 text-xs font-semibold rounded-lg bg-white shadow-sm text-slate-900 transition-all';
            const inactiveClass = 'py-2.5 text-xs font-medium rounded-lg text-slate-500 hover:text-slate-900 transition-all';

            Object.values(btns).forEach(b => b.className = inactiveClass);
            btns[key].className = activeClass;

            const container = document.getElementById('seasonality-pricing-container');
            if (key === 'all-season') {
                container.classList.add('hidden');
            } else {
                container.classList.remove('hidden');
            }
        }

        function addSeasonalityRow() {
            const list = document.getElementById('seasonality-rows-list');
            list.insertAdjacentHTML('beforeend', `
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 bg-white p-3 rounded-xl border border-slate-200 items-end text-xs">
                    <div>
                        <label class="block text-[9px] uppercase font-bold text-slate-400 mb-1">Window Start Date</label>
                        <input type="date" class="w-full p-2 bg-slate-50 border rounded-lg text-xs font-medium">
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase font-bold text-slate-400 mb-1">Window End Date</label>
                        <input type="date" class="w-full p-2 bg-slate-50 border rounded-lg text-xs font-medium">
                    </div>
                    <div>
                        <label class="block text-[9px] uppercase font-bold text-slate-400 mb-1">Custom Season Base Pricing Override ($)</label>
                        <div class="flex space-x-2">
                            <input type="number" class="w-full p-2 bg-slate-50 border rounded-lg text-xs font-mono font-bold text-purple-700" value="1500">
                            <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="text-slate-400 hover:text-red-500 p-2 transition-all"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            `);
        }

        function renderWizCalendar() {
            const grid = document.getElementById('wiz-calendar-grid');
            if (!grid) return;
            const now = new Date();
            const year = now.getFullYear();
            const month = now.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            let html = '';
            for (let i = 0; i < firstDay; i++) {
                html += '<span class="p-1.5 text-slate-300 text-xs"></span>';
            }
            for (let d = 1; d <= daysInMonth; d++) {
                const isToday = d === now.getDate();
                html += `<span onclick="selectCalendarDay(${d})" class="p-1.5 rounded-lg cursor-pointer text-xs font-medium hover:bg-purple-100 transition-all ${isToday ? 'bg-purple-600 text-white font-bold' : 'text-slate-700'}" id="cal-day-${d}">${d}</span>`;
            }
            grid.innerHTML = html;
        }

        function selectCalendarDay(day) {
            const now = new Date();
            calendarStartDate = day;
            const label = document.getElementById('calendar-selection-label');
            if (label) {
                label.textContent = `${now.toLocaleString('default', { month: 'long' })} ${day}, ${now.getFullYear()} — Selected`;
            }
            document.querySelectorAll('[id^="cal-day-"]').forEach(el => {
                el.classList.remove('bg-purple-600', 'text-white', 'font-bold', 'bg-purple-100', 'text-purple-800');
            });
            const selected = document.getElementById(`cal-day-${day}`);
            if (selected) {
                selected.classList.add('bg-purple-600', 'text-white', 'font-bold');
            }
        }

        function selectDurationPreset(nights) {
            const strip = document.getElementById('duration-preset-strip');
            const presets = [7, 14, 21, 28];
            const labels = ['7 Nights Standard', '14 Nights Immersive', '21 Nights Extended', '28 Nights Sovereign'];
            strip.innerHTML = presets.map((n, i) =>
                `<span onclick="selectDurationPreset(${n})" class="px-3 py-2 ${n === nights ? 'bg-slate-900 text-white border-slate-900 shadow-md' : 'bg-slate-100 text-slate-700 border-slate-200 hover:bg-slate-200'} border rounded-xl text-xs font-semibold cursor-pointer transition-all">${labels[i]}</span>`
            ).join('');
            generateDayNodes(nights);
        }

        function applyCustomDaysCount() {
            const input = document.getElementById('custom-days-input');
            const val = parseInt(input.value);
            if (val && val > 0 && val <= 365) {
                generateDayNodes(val);
            } else {
                alert('Please enter a valid number of nights (1–365).');
            }
        }

        function generateDayNodes(count) {
            const container = document.getElementById('day-node-container');
            container.innerHTML = '';
            for (let i = 1; i <= count; i++) {
                container.innerHTML += `<div class="p-3 bg-white border border-slate-200 rounded-xl text-center text-xs relative font-medium text-slate-800">Day ${i} / Night ${i} <span class="absolute top-1 right-1 text-slate-300 hover:text-slate-600 cursor-pointer" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark text-[9px]"></i></span></div>`;
            }
        }

        function appendDayBoxNode() {
            const container = document.getElementById('day-node-container');
            const idx = container.children.length + 1;
            container.innerHTML += `<div class="p-3 bg-white border border-slate-200 rounded-xl text-center text-xs relative font-medium text-slate-800">Day ${idx} / Night ${idx} <span class="absolute top-1 right-1 text-slate-300 hover:text-slate-600 cursor-pointer" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark text-[9px]"></i></span></div>`;
        }

        function addNewCustomRoomNode() {
            const container = document.getElementById('custom-room-nodes-container');
            container.insertAdjacentHTML('beforeend', `
                <div class="border border-slate-200 rounded-xl bg-slate-50/60 overflow-hidden shadow-sm room-node-item">
                    <div class="p-3 bg-slate-100/80 flex flex-col sm:flex-row justify-between items-start sm:items-center text-xs font-semibold text-slate-700 gap-2">
                        <div class="flex items-center space-x-2 w-full sm:w-auto">
                            <i class="fa-regular fa-bed text-slate-400 shrink-0"></i>
                            <input type="text" class="bg-transparent border-b border-transparent hover:border-slate-300 focus:border-purple-500 focus:bg-white p-0.5 text-slate-900 font-bold outline-none w-full sm:w-80 truncate" value="Custom Luxury Room Archetype Suite">
                        </div>
                        <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4">
                            <span class="font-mono text-slate-500 text-[11px]">Calculated Yield Core: <strong class="text-slate-900 font-bold">$2,200</strong></span>
                            <button onclick="this.closest('.room-node-item').remove()" class="text-red-500 hover:text-red-700 text-xs transition-all"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="p-4 bg-white border-t border-slate-100 space-y-4">
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 text-xs">
                            <div class="p-2 bg-slate-50 border rounded-lg"><span class="block text-[9px] uppercase font-bold text-slate-400">👤 Single Occupancy ($)</span><input type="number" class="w-full bg-transparent font-mono font-bold text-slate-900 outline-none mt-0.5" value="2200"></div>
                            <div class="p-2 bg-slate-50 border rounded-lg"><span class="block text-[9px] uppercase font-bold text-slate-400">👥 Double Occupancy ($)</span><input type="number" class="w-full bg-transparent font-mono font-bold text-slate-900 outline-none mt-0.5" value="3100"></div>
                            <div class="p-2 bg-slate-50 border rounded-lg"><span class="block text-[9px] uppercase font-bold text-slate-400">🤝 Twin Sharing ($)</span><input type="number" class="w-full bg-transparent font-mono font-bold text-slate-900 outline-none mt-0.5" value="1600"></div>
                            <div class="p-2 bg-slate-50 border rounded-lg"><span class="block text-[9px] uppercase font-bold text-slate-400">🏢 Dorm Bed Allotment ($)</span><input type="number" class="w-full bg-transparent font-mono font-bold text-slate-900 outline-none mt-0.5" value="750"></div>
                            <div class="p-2 bg-purple-50/60 border border-purple-100 rounded-lg"><span class="block text-[9px] uppercase font-bold text-purple-700">👶 With Kids Surcharge ($)</span><div class="flex items-center space-x-1 mt-0.5"><input type="number" class="w-full bg-transparent font-mono font-bold text-purple-900 outline-none" value="350"><input type="checkbox" checked class="rounded border-slate-300 text-purple-600 h-3.5 w-3.5 focus:ring-0"></div></div>
                        </div>
                        <div class="flex items-center space-x-2 text-xs text-slate-500">
                            <span>Allocated Unit Inventory Block Capacity:</span>
                            <input type="number" class="w-16 p-1 bg-slate-50 border rounded text-center font-bold text-slate-800" value="8">
                        </div>
                    </div>
                </div>
            `);
        }

        function recalcWizAccommodationMatrix() {
            const anchor = parseInt(document.getElementById('wfield-baseprice').value) || 0;
            const villaEl = document.getElementById('wlbl-room-villa');
            const deluxeEl = document.getElementById('wlbl-room-deluxe');
            if (villaEl) villaEl.textContent = `$${(anchor + 1250).toLocaleString()}`;
            if (deluxeEl) deluxeEl.textContent = `$${(anchor + 480).toLocaleString()}`;
        }

        function addTimelineRow() {
            const container = document.getElementById('timeline-rows-container');
            container.insertAdjacentHTML('beforeend', `
                <div class="relative bg-white p-3 border border-slate-100 rounded-xl shadow-sm flex items-center justify-between text-xs hover:border-purple-300 transition-all">
                    <input type="time" class="font-mono text-purple-600 font-bold w-20 bg-transparent border-none outline-none text-xs" value="14:00">
                    <input type="text" class="flex-1 font-semibold text-slate-900 px-3 bg-transparent border-none outline-none text-xs focus:bg-slate-50 rounded" placeholder="Activity or session name...">
                    <div class="flex items-center space-x-2">
                        <input type="text" class="text-[10px] bg-slate-100 px-2 py-0.5 rounded text-slate-500 font-medium w-28 outline-none" placeholder="Location / Tag">
                        <button onclick="this.closest('.relative').remove()" class="text-slate-300 hover:text-red-500 transition-all"><i class="fa-solid fa-xmark text-[10px]"></i></button>
                    </div>
                </div>
            `);
        }
    </script>
@endsection
