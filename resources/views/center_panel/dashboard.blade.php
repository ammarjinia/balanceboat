@extends('layouts.center')

@section('title', 'Center Dashboard — BalanceBoat')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl font-serif font-light text-slate-900">Retreat Blueprint Studio</h1>
            <p class="text-xs text-slate-500 mt-1">Deploy, modify, copy, or instantiate luxury holistic programming profiles into the global availability registry.</p>
        </div>
        <button type="button" class="py-3 px-5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-semibold shadow-md flex items-center space-x-2 transition-all hover:scale-[1.01] glow-ai">
            <i class="fa-solid fa-sparkles text-amber-300"></i>
            <span>Launch Guided Program Studio Wizard</span>
        </button>
    </div>

    <section class="space-y-4">
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
                    <span class="bg-purple-600 text-white text-[8px] px-2 py-1 rounded font-mono font-bold">{{ min(100, max(86, intval($pipelineOccupancyVelocity + 10))) }}% Optimization Rank</span>
                </p>
                <h3 class="text-sm font-bold text-slate-900 truncate mt-1">{{ $topConvertingProgramName }}</h3>
                <p class="text-[9px] text-slate-400">AI Conversion Margin Lift Index: <strong class="text-purple-700">{{ $pipelineOccupancyVelocity > 0 ? number_format($pipelineOccupancyVelocity/10,2) : '4.92' }}%</strong></p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-4">
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all relative group">
                    <div class="absolute top-5 right-5 flex items-center space-x-2">
                        <button type="button" onclick="alert('Cloning blueprint structural metadata to sandbox drafts...')" class="text-xs text-slate-400 hover:text-slate-700 bg-slate-50 hover:bg-slate-100 px-3 py-1 rounded-2xl border border-slate-200 transition-all font-medium"><i class="fa-regular fa-copy mr-1"></i> Smart Duplicate Structure</button>
                        <span class="h-7 px-3 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-2xl flex items-center border border-emerald-200"><span class="h-2 w-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span> Production Active Listing</span>
                    </div>

                    <div class="flex flex-col lg:flex-row items-start gap-5">
                        <div class="h-28 w-full lg:w-40 bg-slate-100 rounded-3xl overflow-hidden shrink-0 border relative">
                            <img src="https://images.unsplash.com/photo-1545205597-3d9d02c29597?auto=format&fit=crop&w=500&q=80" alt="Retreat asset" class="w-full h-full object-cover">
                            <span class="absolute bottom-3 right-3 bg-black/70 text-white text-[9px] font-mono px-2 py-1 rounded-xl">7D / 6N Module</span>
                        </div>
                        <div class="space-y-3 min-w-0 pr-16">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <h4 class="text-xl font-serif font-bold text-slate-900 truncate">{{ $topConvertingProgramName }}</h4>
                                <span class="text-[10px] font-bold tracking-wider uppercase px-3 py-1 bg-purple-100 text-purple-700 rounded-2xl flex items-center border border-purple-200"><i class="fa-solid fa-award mr-1"></i> Best Detox Program Badge</span>
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed font-light">Deep metabolic purification layers leveraging real-time biological diagnostic datasets paired with authentic therapeutic routines, optimized for premium distribution channels.</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4 text-xs">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 gap-3">
                            <div>
                                <span class="block text-[9px] font-medium text-slate-400 uppercase">Baseline Package Base Value</span>
                                <span class="font-mono text-slate-900 font-bold">$1,980 <span class="text-[9px] text-slate-400 font-normal">/ person base</span></span>
                            </div>
                            <div>
                                <span class="block text-[9px] font-medium text-slate-400 uppercase">Live Pipeline Occupancy Velocity</span>
                                <div class="flex items-center space-x-2 mt-1">
                                    <div class="w-20 h-1 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500" style="width: {{ $pipelineOccupancyVelocity }}%"></div>
                                    </div>
                                    <span class="font-bold text-emerald-600 text-[10px]">{{ $pipelineOccupancyVelocity }}% Inventory Lock</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 text-[10px] text-slate-400"><i class="fa-regular fa-calendar-check"></i> Targeted Allocation: June 2026 Distribution Cycle</div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center space-x-2 pb-2 border-b border-slate-100">
                        <i class="fa-solid fa-wand-magic-sparkles text-purple-600 text-sm animate-pulse"></i>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">AI Yield Optimization Insights</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="p-4 bg-purple-50 border border-purple-100 rounded-3xl space-y-2">
                            <span class="text-[9px] font-bold uppercase bg-purple-100 text-purple-700 px-2 py-1 rounded font-mono">Demand Matrix Detection</span>
                            <p class="text-xs text-slate-700 font-light">"7-day Ayurvedic detox structures are processing 42% higher systemic consumer search hits in Central Europe region tracks. Dynamic localization recommended."</p>
                        </div>
                        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-3xl space-y-2">
                            <span class="text-[9px] font-bold uppercase bg-emerald-100 text-emerald-700 px-2 py-1 rounded font-mono">Psychological Pricing Hook</span>
                            <p class="text-xs text-slate-700 font-light">"Activating an early-bird 10% auto-markdown ruleset captures long-tail reservation pipelines, lifting velocity scores by 38%."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
