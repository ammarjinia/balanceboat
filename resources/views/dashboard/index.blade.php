@extends('layouts.center_dashboard')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
    <div class="space-y-6">
        {{-- Stats Grid --}}
        <div class="grid grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Retreats</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_retreats'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-spa text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Active Bookings</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_bookings'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">₹{{ number_format($stats['total_revenue'], 0) }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-rupee-sign text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg border border-gray-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Occupancy</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ round($stats['occupancy_percentage']) }}%</p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i class="fas fa-chart-pie text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            {{-- Upcoming Retreats --}}
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Upcoming Retreats</h2>
                <div class="space-y-3">
                    @foreach ($upcoming_retreats as $retreat)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $retreat['name'] }}</p>
                                <p class="text-sm text-gray-600">{{ $retreat['start_date'] }} - {{ $retreat['end_date'] }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">{{ $retreat['booked'] }}/{{ $retreat['total'] }}</p>
                                <div class="w-16 h-2 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                    <div class="h-full bg-green-500" style="width: {{ $retreat['occupancy'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Bookings --}}
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Bookings</h2>
                <div class="space-y-3">
                    @foreach ($recent_bookings as $booking)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $booking['guest_name'] }}</p>
                                <p class="text-sm text-gray-600">{{ $booking['retreat_name'] }} • {{ $booking['guests'] }}
                                    guests</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">₹{{ number_format($booking['amount'], 0) }}</p>
                                <p class="text-xs text-gray-500">{{ $booking['arrival_date'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
