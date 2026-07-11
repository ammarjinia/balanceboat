@extends('layouts.center')

@section('title', 'Availability & Pricing — BalanceBoat')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-purple-500 mb-1">Center Management</p>
            <h1 class="text-3xl font-serif font-bold text-slate-900">Availability & Pricing</h1>
            <p class="text-slate-500 text-sm mt-1">Define seasonal date-range pricing per accommodation for each experience.</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-emerald-700">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($experiences->isEmpty())

        {{-- Empty State --}}
        <div class="glass rounded-3xl shadow-sm">
            <div class="py-20 flex flex-col items-center text-center px-6">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-purple-50 to-orange-50 border border-purple-100 flex items-center justify-center mb-5 shadow-sm">
                    <i class="fa-regular fa-calendar-days text-3xl text-purple-400"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">No experiences yet</h3>
                <p class="text-sm text-slate-500 max-w-xs mb-6">Create a retreat experience first, then come back here to configure accommodation pricing and availability.</p>
                <a href="{{ route('center-panel.experiences') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white rounded-2xl text-xs font-semibold hover:bg-purple-700 transition-all shadow-sm shadow-purple-200">
                    <i class="fa-regular fa-spa"></i> Go to Experiences
                </a>
            </div>
        </div>

    @else

        {{-- Stats Row --}}
        @php
            $totalConfigured  = $accomCounts->filter(fn($c) => $c > 0)->count();
            $totalPrices      = $priceCounts->sum();
            $totalScheduled   = $scheduleCounts->sum();
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="glass rounded-3xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 flex items-center justify-center shrink-0">
                    <i class="fa-regular fa-spa text-purple-500 text-base"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900 leading-none">{{ $experiences->count() }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Total Experiences</p>
                </div>
            </div>
            <div class="glass rounded-3xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center shrink-0">
                    <i class="fa-regular fa-bed text-emerald-500 text-base"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900 leading-none">{{ $totalConfigured }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">With Room Options</p>
                </div>
            </div>
            <div class="glass rounded-3xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl bg-orange-50 flex items-center justify-center shrink-0">
                    <i class="fa-regular fa-tag text-orange-400 text-base"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900 leading-none">{{ $totalPrices }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Price Ranges</p>
                </div>
            </div>
            <div class="glass rounded-3xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl bg-teal-50 flex items-center justify-center shrink-0">
                    <i class="fa-regular fa-calendar-days text-teal-500 text-base"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900 leading-none">{{ $totalScheduled }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Scheduled Dates</p>
                </div>
            </div>
        </div>

        {{-- Experience Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($experiences as $experience)
            @php
                $rooms      = $accomCounts[$experience->id] ?? 0;
                $ranges     = $priceCounts[$experience->id] ?? 0;
                $isSetup    = $rooms > 0;
            @endphp
            <div class="glass rounded-3xl overflow-hidden shadow-sm card-hover flex flex-col">

                {{-- Thumbnail --}}
                <div class="relative h-40 bg-gradient-to-br from-slate-100 to-slate-200 overflow-hidden shrink-0">
                    @if($experience->thumbnail_image_url)
                        <img src="{{ Storage::disk('s3')->url($experience->thumbnail_image_url) }}"
                             alt="{{ $experience->name }}"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent"></div>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                            <i class="fa-regular fa-spa text-4xl text-slate-300"></i>
                        </div>
                    @endif

                    {{-- Setup status badge --}}
                    <div class="absolute top-3 right-3">
                        @if($isSetup)
                            <span class="inline-flex items-center gap-1 bg-emerald-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-xl shadow-sm">
                                <i class="fa-solid fa-circle-check text-[8px]"></i> Configured
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-amber-400/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-xl shadow-sm">
                                <i class="fa-solid fa-circle-exclamation text-[8px]"></i> Needs Setup
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-5 flex flex-col flex-1 gap-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm leading-snug">{{ $experience->name }}</h3>
                        @if($experience->experience_summary)
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($experience->experience_summary), 90) }}
                            </p>
                        @endif
                    </div>

                    {{-- Counts --}}
                    @php $schedDates = $scheduleCounts[$experience->id] ?? 0; @endphp
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 text-[11px] font-semibold px-2.5 py-1 rounded-xl">
                            <i class="fa-regular fa-bed text-[10px]"></i>
                            {{ $rooms }} {{ Str::plural('room type', $rooms) }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 text-[11px] font-semibold px-2.5 py-1 rounded-xl">
                            <i class="fa-regular fa-tag text-[10px]"></i>
                            {{ $ranges }} {{ Str::plural('price range', $ranges) }}
                        </span>
                        @if($schedDates > 0)
                        <span class="inline-flex items-center gap-1.5 bg-teal-50 text-teal-700 text-[11px] font-semibold px-2.5 py-1 rounded-xl">
                            <i class="fa-regular fa-calendar-days text-[10px]"></i>
                            {{ $schedDates }} {{ Str::plural('start date', $schedDates) }}
                        </span>
                        @endif
                    </div>

                    {{-- CTAs --}}
                    <div class="pt-2 border-t border-slate-100 mt-auto space-y-2">
                        <a href="{{ route('center-panel.availability.manage', $experience->id) }}"
                           class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-xl bg-purple-600 text-white text-xs font-semibold hover:bg-purple-700 active:scale-95 transition-all shadow-sm shadow-purple-200">
                            <i class="fa-regular fa-tag text-[11px]"></i>
                            {{ $isSetup ? 'Edit Pricing' : 'Set Up Pricing' }}
                        </a>
                        @if($isSetup)
                        <a href="{{ route('center-panel.availability.schedule', $experience->id) }}"
                           class="w-full inline-flex items-center justify-center gap-2 py-2 rounded-xl border border-teal-200 text-teal-700 bg-teal-50 text-xs font-semibold hover:bg-teal-100 active:scale-95 transition-all">
                            <i class="fa-regular fa-calendar-days text-[11px]"></i>
                            {{ $schedDates > 0 ? 'Manage Schedule (' . $schedDates . ')' : 'Set Up Schedule' }}
                        </a>
                        @endif
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    @endif

@endsection
