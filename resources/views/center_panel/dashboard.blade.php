@extends('layouts.center')

@section('title', 'Dashboard — BalanceBoat')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl font-serif font-light text-slate-900">Center Overview</h1>
            <p class="text-xs text-slate-500 mt-1">Unified data streams covering demand, bookings, and profile performance for {{ $center->name ?? 'your center' }}.</p>
        </div>
        <a href="{{ route('center-panel.experiences') }}"
            class="py-3 px-5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-semibold shadow-md flex items-center space-x-2 transition-all hover:scale-[1.01] glow-ai shrink-0">
            <i class="fa-regular fa-spa text-purple-300"></i>
            <span>Manage Retreat Programs</span>
        </a>
    </div>

    {{-- KPI Grid --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm card-hover space-y-2">
            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Total Inquiries</p>
            <h3 class="text-3xl font-serif font-bold text-slate-900">{{ number_format($totalInquiries) }}</h3>
            <p class="text-[11px] font-bold {{ $inquiriesDelta['positive'] ? 'text-emerald-600' : 'text-rose-500' }}">
                <i class="fa-solid fa-arrow-{{ $inquiriesDelta['positive'] ? 'up' : 'down' }}"></i> {{ $inquiriesDelta['value'] }}% vs last month
            </p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm card-hover space-y-2">
            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Total Bookings</p>
            <h3 class="text-3xl font-serif font-bold text-slate-900">{{ number_format($totalBookings) }}</h3>
            <p class="text-[11px] font-bold {{ $bookingsDelta['positive'] ? 'text-emerald-600' : 'text-rose-500' }}">
                <i class="fa-solid fa-arrow-{{ $bookingsDelta['positive'] ? 'up' : 'down' }}"></i> {{ $bookingsDelta['value'] }}% vs last month
            </p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm card-hover space-y-2">
            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Monthly Booking Value</p>
            <h3 class="text-3xl font-serif font-bold text-slate-900">{{ $currencySymbol }}{{ number_format($monthlyRevenue) }}</h3>
            <p class="text-[11px] font-bold {{ $revenueDelta['positive'] ? 'text-emerald-600' : 'text-rose-500' }}">
                <i class="fa-solid fa-arrow-{{ $revenueDelta['positive'] ? 'up' : 'down' }}"></i> {{ $revenueDelta['value'] }}% vs last month
            </p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm card-hover space-y-2">
            <p class="text-[10px] uppercase tracking-wider font-bold text-purple-600">Active Retreats</p>
            <h3 class="text-3xl font-serif font-bold text-purple-600">{{ $activeExperiences }} <span class="text-sm text-slate-400 font-sans font-normal">/ {{ $totalExperiences }}</span></h3>
            <p class="text-[11px] font-bold text-slate-400">Listings live in distribution</p>
        </div>
    </section>

    {{-- Two Column Analytics Layout --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Left Column --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Retreat Performance --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-regular fa-chart-mixed text-purple-600"></i> Retreat Performance Leaderboard
                    </h3>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1 bg-purple-50 text-purple-700 rounded-2xl border border-purple-100">Top {{ $retreatPerformance->count() }} of {{ $totalExperiences }}</span>
                </div>

                @if($retreatPerformance->count() > 0)
                    <table class="w-full">
                        <thead>
                            <tr class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                                <th class="text-left pb-2">Retreat Program</th>
                                <th class="text-left pb-2">Status</th>
                                <th class="text-right pb-2">Inquiries</th>
                                <th class="text-right pb-2">Bookings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($retreatPerformance as $exp)
                                <tr class="border-t border-slate-100">
                                    <td class="py-3 pr-3">
                                        <p class="text-xs font-semibold text-slate-900 truncate max-w-[220px]">{{ $exp->name ?? 'Retreat Program' }}</p>
                                        <p class="text-[10px] text-slate-400">ID: RTP-{{ str_pad($exp->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </td>
                                    <td class="py-3">
                                        @if($exp->is_draft)
                                            <span class="h-6 px-2.5 bg-amber-50 text-amber-700 text-[9px] font-bold uppercase tracking-wider rounded-2xl inline-flex items-center border border-amber-200">
                                                <span class="h-1.5 w-1.5 bg-amber-400 rounded-full mr-1.5"></span> Draft
                                            </span>
                                        @else
                                            <span class="h-6 px-2.5 bg-emerald-50 text-emerald-700 text-[9px] font-bold uppercase tracking-wider rounded-2xl inline-flex items-center border border-emerald-200">
                                                <span class="h-1.5 w-1.5 bg-emerald-500 rounded-full mr-1.5"></span> Live
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right text-xs font-mono text-slate-700">{{ $exp->inquiries_count }}</td>
                                    <td class="py-3 text-right text-xs font-mono font-bold text-slate-900">{{ $exp->bookings_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-xs text-slate-400 text-center py-8">No retreat programs yet. Create one to start tracking performance.</p>
                @endif
            </div>

            {{-- Booking Pipeline Funnel --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-regular fa-filter-list text-purple-600"></i> Booking Pipeline Funnel
                    </h3>
                    <span class="text-[11px] text-slate-400">By stage, all-time</span>
                </div>

                @if(count($pipelineFunnel) > 0)
                    <div class="space-y-3">
                        @foreach($pipelineFunnel as $stage)
                            <div class="flex items-center gap-3">
                                <span class="w-28 shrink-0 text-xs font-medium text-slate-600">{{ $stage['label'] }}</span>
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ in_array($stage['stage'], ['cancelled','refunded']) ? 'bg-rose-400' : 'bg-purple-500' }}" style="width: {{ max(4, round(($stage['count'] / $maxStageCount) * 100)) }}%;"></div>
                                </div>
                                <span class="w-10 shrink-0 text-right text-xs font-mono font-bold text-slate-900">{{ $stage['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 text-center py-8">No bookings recorded yet for your retreat programs.</p>
                @endif
            </div>

            {{-- Recent Inquiries --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-regular fa-inbox text-purple-600"></i> Recent Inquiry Activity
                    </h3>
                    <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1 bg-amber-50 text-amber-700 rounded-2xl border border-amber-100">{{ $totalInquiries }} All-Time</span>
                </div>

                @if($recentInquiries->count() > 0)
                    <table class="w-full">
                        <thead>
                            <tr class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                                <th class="text-left pb-2">Prospect</th>
                                <th class="text-left pb-2">Retreat Program</th>
                                <th class="text-right pb-2">Received</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentInquiries as $inquiry)
                                <tr class="border-t border-slate-100">
                                    <td class="py-3 pr-3">
                                        <p class="text-xs font-semibold text-slate-900">{{ trim(($inquiry->name ?? '') . ' ' . ($inquiry->lastname ?? '')) ?: 'Unknown Prospect' }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $inquiry->email ?? '—' }}</p>
                                    </td>
                                    <td class="py-3 text-xs text-slate-600">{{ $inquiry->experience->name ?? 'General Inquiry' }}</td>
                                    <td class="py-3 text-right text-xs text-slate-400">{{ $inquiry->created_at ? $inquiry->created_at->diffForHumans() : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-xs text-slate-400 text-center py-8">No inquiries received yet.</p>
                @endif
            </div>

        </div>

        {{-- Right Column --}}
        <div class="space-y-6">

            {{-- Profile Completeness Engine --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-shield-check text-purple-600"></i> Profile Completeness Engine
                </h3>

                <div class="flex items-center gap-5 bg-gradient-to-br from-purple-50/60 to-cyan-50/40 border border-purple-100/60 rounded-2xl p-4 mb-5">
                    <svg width="84" height="84" viewBox="0 0 100 100" class="-rotate-90 shrink-0">
                        <defs>
                            <linearGradient id="strengthGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#8B5CF6" />
                                <stop offset="100%" stop-color="#06B6D4" />
                            </linearGradient>
                        </defs>
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#EBEBFC" stroke-width="8" />
                        <circle cx="50" cy="50" r="40" fill="none" stroke="url(#strengthGradient)" stroke-width="8" stroke-linecap="round"
                            stroke-dasharray="251.2" stroke-dashoffset="{{ round(251.2 * (1 - $completenessScore / 100), 1) }}" />
                    </svg>
                    <div>
                        <h4 class="text-xl font-serif font-bold text-slate-900">{{ $completenessScore }}% Score</h4>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Complete profiles convert more visitors into qualified inquiries.</p>
                    </div>
                </div>

                @if(count($missingFields) > 0)
                    <h5 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Outstanding missing fields</h5>
                    <ul class="space-y-2">
                        @foreach(array_slice($missingFields, 0, 5) as $missing)
                            <li class="flex items-start gap-2 text-xs text-slate-600 pb-2 border-b border-dashed border-slate-100 last:border-none">
                                <span class="text-amber-500 font-bold">✦</span>
                                <span>{{ $missing }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('center-panel.settings') }}" class="mt-4 w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-semibold transition-all flex items-center justify-center gap-2">
                        <i class="fa-regular fa-user-gear"></i> Complete Center Profile
                    </a>
                @else
                    <p class="text-xs text-emerald-600 font-semibold flex items-center gap-2"><i class="fa-solid fa-circle-check"></i> Your profile is fully complete.</p>
                @endif
            </div>

            {{-- Top Performer --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-trophy text-amber-500"></i> Top Performing Retreat
                </h3>

                @if($topPerformer && $topPerformer->activity_score > 0)
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex justify-between items-center mb-3">
                        <span class="text-xs font-medium text-slate-500">Combined Activity Score</span>
                        <span class="text-lg font-serif font-bold text-purple-600">{{ $topPerformer->activity_score }} pts</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Driven primarily by <strong class="text-slate-900">{{ $topPerformer->name }}</strong>, with {{ $topPerformer->bookings_count }} booking(s) and {{ $topPerformer->inquiries_count }} inquiry/inquiries recorded to date.
                    </p>
                @else
                    <p class="text-xs text-slate-400 text-center py-6">No activity recorded yet. Once your retreats start receiving inquiries or bookings, the top performer will appear here.</p>
                @endif
            </div>

        </div>
    </div>
@endsection
