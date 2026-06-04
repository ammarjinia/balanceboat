@extends('layouts.center')

@section('title', 'Center Settings — BalanceBoat')

@section('content')
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl font-serif font-bold text-slate-900">Center Settings</h1>
            <p class="text-slate-600 text-sm mt-1">Manage your center information and preferences.</p>
        </div>
        <a href="{{ route('center-panel.dashboard') }}" class="px-4 py-2 bg-purple-600 text-white rounded-2xl text-xs font-semibold hover:bg-purple-700 transition-all inline-flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-4 flex items-center gap-3 text-sm text-emerald-700">
            <i class="fa-solid fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-3xl p-4 text-sm text-rose-700 space-y-2">
            @foreach ($errors->all() as $error)
                <p class="flex items-center gap-2"><i class="fa-solid fa-exclamation-circle"></i>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('center-panel.settings.update') }}" class="glass rounded-3xl p-8 shadow-sm space-y-6">
        @csrf

        <div class="grid gap-6">
            <div class="space-y-2">
                <label for="name" class="block text-sm font-semibold text-slate-900">Center Name</label>
                <input id="name" name="name" type="text" required value="{{ old('name', $center->name ?? '') }}" class="w-full rounded-3xl border border-slate-200 bg-white/90 px-4 py-3 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 outline-none" placeholder="Enter center name">
            </div>
            <div class="space-y-2">
                <label for="email_address" class="block text-sm font-semibold text-slate-900">Email Address</label>
                <input id="email_address" name="email_address" type="email" value="{{ old('email_address', $center->email_address ?? '') }}" class="w-full rounded-3xl border border-slate-200 bg-white/90 px-4 py-3 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 outline-none" placeholder="Enter email address">
            </div>
            <div class="space-y-2">
                <label for="contact_number" class="block text-sm font-semibold text-slate-900">Contact Number</label>
                <input id="contact_number" name="contact_number" type="tel" value="{{ old('contact_number', $center->contact_number ?? '') }}" class="w-full rounded-3xl border border-slate-200 bg-white/90 px-4 py-3 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 outline-none" placeholder="Enter contact number">
            </div>
            <div class="space-y-2">
                <label for="address_of_center" class="block text-sm font-semibold text-slate-900">Center Address</label>
                <input id="address_of_center" name="address_of_center" type="text" value="{{ old('address_of_center', $center->address_of_center ?? '') }}" class="w-full rounded-3xl border border-slate-200 bg-white/90 px-4 py-3 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 outline-none" placeholder="Enter center address">
            </div>
        </div>

        <div class="border-t border-slate-200 pt-6 mt-6 space-y-4">
            <h2 class="text-lg font-semibold text-slate-900">Additional Information</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 bg-white/90 p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-400">City</p>
                    <p class="mt-2 text-sm text-slate-900">{{ $center->city ?? 'Not provided' }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white/90 p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Country</p>
                    <p class="mt-2 text-sm text-slate-900">{{ $center->country ?? 'Not provided' }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white/90 p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Center Type</p>
                    <p class="mt-2 text-sm text-slate-900">{{ $center->center_type ?? 'Not specified' }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-white/90 p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Status</p>
                    <p class="mt-2 text-sm text-emerald-700"><i class="fa-solid fa-circle text-emerald-600 text-[10px] mr-2"></i>Active</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:justify-between gap-3 pt-6 border-t border-slate-200">
            <a href="{{ route('center-panel.dashboard') }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition-all">Cancel</a>
            <button type="submit" class="inline-flex items-center justify-center rounded-3xl bg-purple-600 px-6 py-3 text-sm font-semibold text-white hover:bg-purple-700 transition-all gap-2">
                <i class="fa-solid fa-save"></i>
                Save Changes
            </button>
        </div>
    </form>

    <div class="glass rounded-3xl p-8 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Account & Security</h2>
        <div class="space-y-4">
            <div class="flex flex-col md:flex-row md:justify-between gap-4 md:items-center rounded-3xl border border-slate-200 bg-white/90 p-4">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Change Password</p>
                    <p class="text-xs text-slate-500 mt-1">Update your account password.</p>
                </div>
                <button class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition-all">Change</button>
            </div>
            <div class="flex flex-col md:flex-row md:justify-between gap-4 md:items-center rounded-3xl border border-slate-200 bg-white/90 p-4">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Two-Factor Authentication</p>
                    <p class="text-xs text-slate-500 mt-1">Enhance your account security.</p>
                </div>
                <button class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition-all">Enable</button>
            </div>
        </div>
    </div>
@endsection
