@extends('layouts.center')

@section('title', 'Center Bookings — BalanceBoat')

@section('content')
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl font-serif font-bold text-slate-900">Bookings</h1>
            <p class="text-slate-600 text-sm mt-1">View and manage all bookings for your center.</p>
        </div>
        <a href="{{ route('center-panel.dashboard') }}" class="px-4 py-2 bg-purple-600 text-white rounded-2xl text-xs font-semibold hover:bg-purple-700 transition-all inline-flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>

    <div class="glass rounded-3xl p-6 shadow-sm">
        @if($bookings && count($bookings) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">#ID</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Date</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Guest</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Amount</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Order Status</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Payment Status</th>
                            <th class="text-center py-3 px-4 font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            @php $userInfo = $booking->userInfo; @endphp
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-all">
                                <td class="py-3 px-4 font-bold text-purple-600">#{{ $booking->id }}</td>
                                <td class="py-3 px-4 text-xs text-slate-600">{{ $booking->created_at ? $booking->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td class="py-3 px-4 text-slate-900">{{ $userInfo ? $userInfo->firstname . ' ' . $userInfo->lastname : 'Guest' }}</td>
                                <td class="py-3 px-4 font-semibold">{{ $booking->booking_currency ?? '$' }} {{ number_format($booking->pay_amount ?? 0, 2) }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $booking->order_status == 'confirmed' ? 'bg-emerald-100 text-emerald-700' : ($booking->order_status == 'pending' ? 'bg-yellow-100 text-yellow-700' : ($booking->order_status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700')) }}">
                                        {{ ucfirst($booking->order_status ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $booking->payment_status == 'paid' ? 'bg-emerald-100 text-emerald-700' : ($booking->payment_status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-700') }}">
                                        {{ ucfirst($booking->payment_status ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <button class="text-purple-600 hover:underline text-xs">Details</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm text-slate-600">
                    <p>Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} bookings</p>
                    <div class="flex flex-wrap gap-2">{{ $bookings->links() }}</div>
                </div>
            @endif
        @else
            <div class="text-center py-16">
                <i class="fa-solid fa-calendar-xmark text-4xl text-slate-300 mb-3"></i>
                <p class="text-slate-500">No bookings found yet.</p>
            </div>
        @endif
    </div>
@endsection
