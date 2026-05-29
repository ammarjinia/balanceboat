@extends('layouts.center_dashboard')

@section('title', 'Account Settings')
@section('page_title', 'Account Information')

@section('content')
    <div class="space-y-6">
        {{-- AI Trust Score --}}
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">AI Trust Score</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $ai_trust_score['score'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $ai_trust_score['status'] }}</p>
                    </div>
                    <div class="text-6xl text-yellow-400">⭐</div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6 col-span-2">
                <p class="text-gray-600 text-sm font-semibold mb-4">Account Completion</p>
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-900">{{ $completion_percentage }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-3 rounded-full"
                        style="width: {{ $completion_percentage }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Complete your profile to reach 100%</p>
            </div>
        </div>

        {{-- Center Information Form --}}
        <form method="POST" action="{{ route('dashboard.account.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            {{-- Basic Information --}}
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Center Information</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Center Name</label>
                        <input type="text" name="name" value="{{ $center->name }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Business Name</label>
                        <input type="text" name="business_name" value="{{ $center->business_name }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email_address" value="{{ $center->email_address }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                        <input type="text" name="contact_number" value="{{ $center->contact_number }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            {{-- Tax & Billing --}}
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Tax & Billing</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">GST Number</label>
                        <input type="text" name="gst_number" placeholder="e.g. 22AAAAA0000A1Z5"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">PAN Number</label>
                        <input type="text" name="pan_number" placeholder="e.g. AAAAA0000A"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Billing Address</label>
                        <input type="text" name="billing_address" value="{{ $center->address_of_center }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            {{-- Payout Account --}}
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Payout Account</h2>
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Account Holder Name</label>
                        <input type="text" name="account_holder_name"
                            value="{{ $payout_account?->account_holder_name }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ $payout_account?->bank_name }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Account Number</label>
                        <input type="text" name="account_number" value="{{ $payout_account?->account_number }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">IFSC Code</label>
                        <input type="text" name="ifsc_code" value="{{ $payout_account?->ifsc_code }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Payout Cycle</label>
                        <select name="preferred_payout_cycle"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="weekly"
                                {{ $payout_account?->preferred_payout_cycle === 'weekly' ? 'selected' : '' }}>Weekly
                            </option>
                            <option value="bi-weekly"
                                {{ $payout_account?->preferred_payout_cycle === 'bi-weekly' ? 'selected' : '' }}>Bi-weekly
                            </option>
                            <option value="monthly"
                                {{ $payout_account?->preferred_payout_cycle === 'monthly' ? 'selected' : '' }}>Monthly
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">UPI ID</label>
                        <input type="text" name="upi_id" value="{{ $payout_account?->upi_id }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end gap-4">
                <button type="button" onclick="window.history.back()"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection
