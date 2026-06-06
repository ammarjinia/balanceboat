@extends('layouts.center')

@section('title', 'Dashboard — BalanceBoat')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl font-serif font-light text-slate-900">Dashboard</h1>
            <p class="text-xs text-slate-500 mt-1">Analytics and performance overview will be available here soon.</p>
        </div>
    </div>

    <div class="flex flex-col items-center justify-center py-24 space-y-4">
        <div class="h-20 w-20 rounded-3xl bg-slate-100 flex items-center justify-center">
            <i class="fa-regular fa-chart-line text-3xl text-slate-300"></i>
        </div>
        <h3 class="text-lg font-serif font-semibold text-slate-700">Dashboard Coming Soon</h3>
        <p class="text-xs text-slate-400 max-w-sm text-center leading-relaxed">The analytics dashboard is currently under construction. Head to the Retreat Management section to manage your programs.</p>
        <a href="{{ route('center-panel.experiences') }}" class="mt-2 py-2.5 px-5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-semibold transition-all flex items-center space-x-2 shadow-md">
            <i class="fa-regular fa-spa text-sm"></i>
            <span>Go to Retreat Management</span>
        </a>
    </div>
@endsection
