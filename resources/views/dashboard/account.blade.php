@extends('layouts.center_dashboard')

@section('main-content')
    <div class="max-w-6xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Account Settings</h1>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-8">
            {{-- Trust Score Card --}}
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-900">AI Trust Score</h3>
                    <span class="text-3xl font-bold text-purple-600">{{ $ai_trust_score }}%</span>
                </div>
                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-green-500 to-blue-500" style="width: {{ $ai_trust_score }}%">
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-3">Complete your profile to increase trust score</p>
            </div>

            {{-- Completion Card --}}
            <div class="bg-gradient-to-br from-blue-50 to-green-50 rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-900">Profile Completion</h3>
                    <span class="text-3xl font-bold text-blue-600">{{ $completion_percentage }}%</span>
                </div>
                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-green-500"
                        style="width: {{ $completion_percentage }}%"></div>
                </div>
            </div>

            {{-- Security Card --}}
            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-2xl p-6 border border-green-200">
                <h3 class="font-bold text-gray-900 mb-4">Security Health</h3>
                <div class="space-y-2 text-sm">
                    @foreach ($security_checks as $check => $status)
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full"
                                :class="'{{ $status['status'] }}'
                                === 'verified' ? 'bg-green-500' : 'bg-gray-300'"></span>
                            <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $check)) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Center Information Form --}}
        <form method="POST" action="{{ route('account.update') }}" class="space-y-8">
            @csrf @method('PATCH')

            {{-- Basic Info --}}
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Center Information</h2>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Center Name *</label>
                        <input type="text" name="center_name" value="{{ old('center_name', $center->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Business Name</label>
                        <input type="text" name="business_name"
                            value="{{ old('business_name', $center->business_name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Email *</label>
                        <input type="email" name="email_address"
                            value="{{ old('email_address', $center->email_address) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Phone *</label>
                        <input type="tel" name="phone_number"
                            value="{{ old('phone_number', $center->contact_number) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            {{-- Tax & Bank Details --}}
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Tax & Bank Details</h2>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">GST Number</label>
                        <input type="text" name="gst_number" value="{{ old('gst_number', $center->gst_number) }}"
                            placeholder="15-character GST number"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">PAN Number</label>
                        <input type="text" name="pan_number" value="{{ old('pan_number', $center->pan_number) }}"
                            placeholder="10-character PAN" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-4">Payout Account</h3>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Account Holder Name</label>
                        <input type="text" name="account_holder_name"
                            value="{{ old('account_holder_name', $payout_accounts->first()?->account_holder_name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Bank Name</label>
                        <input type="text" name="bank_name"
                            value="{{ old('bank_name', $payout_accounts->first()?->bank_name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Account Number</label>
                        <input type="text" name="account_number"
                            value="{{ old('account_number', $payout_accounts->first()?->account_number) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">IFSC Code</label>
                        <input type="text" name="ifsc_code"
                            value="{{ old('ifsc_code', $payout_accounts->first()?->ifsc_code) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">UPI ID</label>
                        <input type="text" name="upi_id"
                            value="{{ old('upi_id', $payout_accounts->first()?->upi_id) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Payout Frequency</label>
                        <select name="preferred_payout_cycle" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            <option value="weekly">Weekly</option>
                            <option value="bi-weekly">Bi-weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex gap-4">
                <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-lg hover:shadow-lg transition-shadow">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection
