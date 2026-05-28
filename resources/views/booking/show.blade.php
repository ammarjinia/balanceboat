@extends('layouts.center_dashboard')

@section('main-content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Booking #{{ $booking->id }}</h1>
                <p class="text-gray-600 mt-1">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold"
                        :class="'{{ $booking->order_status }}'
                        === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                        {{ ucfirst($booking->order_status) }}
                    </span>
                </p>
            </div>
            @if ($booking->canBeCancelled())
                <form method="POST" action="{{ route('booking.cancel', $booking) }}"
                    onsubmit="return confirm('Cancel this booking?')">
                    @csrf @method('POST')
                    <button type="submit"
                        class="px-6 py-2 bg-red-100 text-red-700 font-semibold rounded-lg hover:bg-red-200">
                        Cancel Booking
                    </button>
                </form>
            @endif
        </div>

        <div class="grid grid-cols-3 gap-6 mb-8">
            {{-- Booking Details --}}
            <div class="col-span-2 bg-white rounded-2xl p-8 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Booking Details</h2>

                <dl class="space-y-6">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Retreat</dt>
                        <dd class="font-semibold text-gray-900">{{ $booking->experience->name }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-gray-600">Accommodation</dt>
                        <dd class="font-semibold text-gray-900">{{ $booking->accommodation?->title }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-gray-600">Arrival Date</dt>
                        <dd class="font-semibold text-gray-900">{{ $booking->arrival_date?->format('M d, Y') }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-gray-600">Departure Date</dt>
                        <dd class="font-semibold text-gray-900">{{ $booking->end_date_time?->format('M d, Y') }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-gray-600">Number of Guests</dt>
                        <dd class="font-semibold text-gray-900">{{ $booking->guest_count }}</dd>
                    </div>

                    <div class="border-t pt-6 flex justify-between">
                        <dt class="text-gray-600 font-semibold">Total Amount</dt>
                        <dd class="font-bold text-2xl text-purple-600">₹{{ number_format($booking->pay_amount) }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Guest Info --}}
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Guest Information</h2>

                <div class="space-y-4">
                    <div>
                        <p class="text-gray-600 text-sm">Name</p>
                        <p class="font-semibold text-gray-900">
                            {{ $booking->userInfo?->firstname }} {{ $booking->userInfo?->lastname }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600 text-sm">Email</p>
                        <p class="font-semibold text-gray-900">{{ $booking->userInfo?->email }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600 text-sm">Phone</p>
                        <p class="font-semibold text-gray-900">{{ $booking->userInfo?->phone }}</p>
                    </div>

                    @if ($booking->addressInfo)
                        <div>
                            <p class="text-gray-600 text-sm">City</p>
                            <p class="font-semibold text-gray-900">{{ $booking->addressInfo?->billing_city }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Payment & Transaction --}}
        @if ($booking->transactionInfo)
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Payment Details</h2>

                <div class="grid grid-cols-4 gap-6">
                    <div>
                        <p class="text-gray-600 text-sm">Transaction ID</p>
                        <p class="font-semibold text-gray-900">{{ $booking->transactionInfo?->tracking_id ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600 text-sm">Status</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst($booking->payment_status) }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600 text-sm">Mode</p>
                        <p class="font-semibold text-gray-900">{{ $booking->transactionInfo?->payment_mode ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600 text-sm">Date</p>
                        <p class="font-semibold text-gray-900">
                            {{ $booking->transactionInfo->trans_date?->format('M d, Y') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
