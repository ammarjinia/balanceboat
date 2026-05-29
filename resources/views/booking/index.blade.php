@extends('layouts.center_dashboard')

@section('title', 'Bookings')
@section('page_title', 'Manage Bookings')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Guest</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Retreat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Guests</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $booking->userInfo->firstname }}
                                    {{ $booking->userInfo->lastname }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->userInfo->email }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-900 font-semibold">{{ $booking->experience->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $booking->arrival_date->format('M d') }} -
                                {{ $booking->end_date_time->format('M d') }}</td>
                            <td class="px-6 py-4 text-gray-900 font-semibold">{{ $booking->guest_count }}</td>
                            <td class="px-6 py-4 text-gray-900 font-bold">₹{{ number_format($booking->pay_amount, 0) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                    {{ ucfirst($booking->order_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('booking.show', $booking) }}"
                                    class="text-purple-600 hover:text-purple-700 font-semibold text-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No bookings yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $bookings->links() }}
    </div>
@endsection
