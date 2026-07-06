@extends('layouts.center')

@section('title', 'Commission Engine — BalanceBoat')

@section('content')

    @php
        $tierLabels = [
            15 => ['name' => 'Baseline Discovery', 'color' => 'bg-neutral-100 text-neutral-600'],
            20 => ['name' => 'Trust & Validation', 'color' => 'bg-cyan-50 text-cyan-700'],
            25 => ['name' => 'Community Acceleration', 'color' => 'bg-emerald-50 text-emerald-700'],
            30 => ['name' => 'Amplified Footprint', 'color' => 'bg-indigo-50 text-indigo-700'],
            35 => ['name' => 'Ecosystem Integration', 'color' => 'bg-violet-50 text-violet-700'],
            40 => ['name' => 'Absolute Market Canopy', 'color' => 'bg-pink-50 text-pink-700'],
            45 => ['name' => 'Sovereign Legacy', 'color' => 'bg-orange-50 text-orange-700'],
        ];
        $tierKeys = array_keys($tierLabels);
    @endphp

    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-purple-500 mb-1">Center Management</p>
            <h1 class="text-3xl font-serif font-bold text-slate-900">Commission Engine</h1>
            <p class="text-slate-500 text-sm mt-1">Align your commission rate per retreat to unlock deeper marketplace placement tiers.</p>
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
                    <i class="fa-solid fa-percent text-3xl text-purple-400"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">No retreats yet</h3>
                <p class="text-sm text-slate-500 max-w-xs mb-6">Create a retreat experience first, then come back here to calibrate its commission alignment.</p>
                <a href="{{ route('center-panel.experiences') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white rounded-2xl text-xs font-semibold hover:bg-purple-700 transition-all shadow-sm shadow-purple-200">
                    <i class="fa-regular fa-spa"></i> Go to Retreats
                </a>
            </div>
        </div>

    @else

        {{-- Retreat Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($experiences as $experience)
            @php
                $rawCommission = $experience->commission ? (float) $experience->commission : null;
                $closest = null;
                if ($rawCommission !== null) {
                    $closest = $tierKeys[0];
                    foreach ($tierKeys as $pct) {
                        if (abs($pct - $rawCommission) < abs($closest - $rawCommission)) $closest = $pct;
                    }
                }
                $tier = $closest ? $tierLabels[$closest] : null;
            @endphp
            <div class="glass rounded-3xl overflow-hidden shadow-sm card-hover flex flex-col">

                <div class="relative h-40 bg-gradient-to-br from-slate-100 to-slate-200 overflow-hidden shrink-0">
                    @if($experience->thumbnail_image_url)
                        <img src="{{ Storage::disk('azure')->url($experience->thumbnail_image_url) }}"
                             alt="{{ $experience->name }}"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent"></div>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                            <i class="fa-regular fa-spa text-4xl text-slate-300"></i>
                        </div>
                    @endif

                    <div class="absolute top-3 right-3">
                        @if($rawCommission !== null)
                            <span class="inline-flex items-center gap-1 bg-purple-600/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-xl shadow-sm">
                                <i class="fa-solid fa-percent text-[8px]"></i> {{ number_format($rawCommission, 0) }}%
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-amber-400/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-xl shadow-sm">
                                <i class="fa-solid fa-circle-exclamation text-[8px]"></i> Not Set
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-5 flex flex-col flex-1 gap-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm leading-snug">{{ $experience->name }}</h3>
                        @if($experience->experience_summary)
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($experience->experience_summary), 90) }}
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        @if($tier)
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold px-2.5 py-1 rounded-xl {{ $tier['color'] }}">
                                <i class="fa-solid fa-sparkles text-[10px]"></i> {{ $tier['name'] }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-500 text-[11px] font-semibold px-2.5 py-1 rounded-xl">
                                <i class="fa-regular fa-circle-question text-[10px]"></i> No alignment set
                            </span>
                        @endif
                    </div>

                    <div class="pt-2 border-t border-slate-100 mt-auto">
                        <a href="{{ route('center-panel.commission.manage', $experience->id) }}"
                           class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-xl bg-purple-600 text-white text-xs font-semibold hover:bg-purple-700 active:scale-95 transition-all shadow-sm shadow-purple-200">
                            <i class="fa-solid fa-sliders text-[11px]"></i>
                            {{ $rawCommission !== null ? 'Calibrate Commission' : 'Set Up Commission' }}
                        </a>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    @endif

@endsection
