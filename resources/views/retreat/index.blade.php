@extends('layouts.center_dashboard')

@section('title', 'Retreats')
@section('page_title', 'Manage Retreats')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Your Retreats</h1>
        <a href="{{ route('retreat.create') }}" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition">
            <i class="fas fa-plus mr-2"></i>Create New Retreat
        </a>
    </div>

    <div class="grid gap-6">
        @forelse($retreats as $retreat)
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-xl font-bold text-gray-900">{{ $retreat['name'] }}</h3>
                            <span class="px-2 py-1 bg-{{ $retreat['status'] === 'Draft' ? 'yellow-100 text-yellow-800' : 'green-100 text-green-800' }} rounded-full text-xs font-semibold">
                                {{ $retreat['status'] }}
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-3">{{ $retreat['start_date'] }} - {{ $retreat['end_date'] }}</p>

                        <div class="grid grid-cols-4 gap-4 mb-4">
                            <div>
                                <p class="text-xs text-gray-500 font-semibold">PRICE</p>
                                <p class="text-lg font-bold text-gray-900">₹{{ number_format($retreat['price'], 0) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold">CAPACITY</p>
                                <p class="text-lg font-bold text-gray-900">{{ $retreat['booked'] }}/{{ $retreat['total'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold">BOOKINGS</p>
                                <p class="text-lg font-bold text-gray-900">{{ $retreat['bookings'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold">REVENUE</p>
                                <p class="text-lg font-bold text-purple-600">₹{{ number_format($retreat['revenue'], 0) }}</p>
                            </div>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $retreat['occupancy_percent'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ round($retreat['occupancy_percent']) }}% Occupied</p>
                    </div>

                    <div class="flex flex-col gap-2 ml-6">
                        <a href="{{ route('retreat.edit', $retreat['id']) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <a href="{{ route('retreat.show', $retreat['id']) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                        <a href="#" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                            <i class="fas fa-copy mr-1"></i>Duplicate
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                <i class="fas fa-spa text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Retreats Yet</h3>
                <p class="text-gray-600 mb-6">Create your first retreat to get started</p>
                <a href="{{ route('retreat.create') }}" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Create Your First Retreat
                </a>
            </div>
        @endforelse
    </div>
@endsection