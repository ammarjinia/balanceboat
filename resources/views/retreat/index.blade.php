@extends('layouts.center_dashboard')

@section('main-content')
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Retreats</h1>
                <p class="text-gray-600 mt-1">Manage your wellness retreat programs</p>
            </div>
            <a href="{{ route('retreat.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-lg hover:shadow-lg transition-shadow">
                + Create Retreat
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <div class="text-gray-600 text-sm font-semibold uppercase mb-2">Total Retreats</div>
            <div class="text-3xl font-bold text-gray-900">{{ $retreats->total() }}</div>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <div class="text-gray-600 text-sm font-semibold uppercase mb-2">Published</div>
            <div class="text-3xl font-bold text-green-600">{{ $retreats->where('is_bookable', true)->count() }}</div>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <div class="text-gray-600 text-sm font-semibold uppercase mb-2">Total Bookings</div>
            <div class="text-3xl font-bold text-blue-600">{{ $total_bookings }}</div>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-200">
            <div class="text-gray-600 text-sm font-semibold uppercase mb-2">Total Revenue</div>
            <div class="text-3xl font-bold text-purple-600">₹{{ number_format($total_revenue) }}</div>
        </div>
    </div>

    {{-- Retreats Grid --}}
    <div class="grid grid-cols-3 gap-6">
        @foreach ($retreats as $retreat)
            <div
                class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                {{-- Image --}}
                <div class="relative h-48 overflow-hidden bg-gradient-to-br from-purple-100 to-pink-100">
                    @if ($retreat->banner_image_url)
                        <img src="{{ Storage::disk('azure')->url($retreat->banner_image_url) }}" alt="{{ $retreat->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl">🧘</div>
                    @endif

                    {{-- Badge --}}
                    <div class="absolute top-4 right-4">
                        @if ($retreat->is_draft)
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                ✏️ Draft
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                ✓ Live
                            </span>
                        @endif
                    </div>

                    {{-- Occupancy --}}
                    <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2">
                        <div class="text-xs font-semibold text-gray-600">
                            {{ $retreat->occupied_spaces }}/{{ $retreat->total_spaces }} Booked
                        </div>
                        <div class="w-24 h-1.5 bg-gray-200 rounded-full mt-1 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-green-500 to-blue-500"
                                style="width: {{ $retreat->occupancy_percentage }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-5">
                    <h3 class="font-bold text-gray-900 text-lg mb-1 line-clamp-2">{{ $retreat->name }}</h3>

                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{!! $retreat->experience_summary !!}</p>

                    <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                        <div>
                            <div class="text-gray-500 text-xs">Dates</div>
                            <div class="font-semibold text-gray-900">
                                {{ $retreat->start_date_time?->format('M d') ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-gray-500 text-xs">Price</div>
                            <div class="font-semibold text-gray-900">
                                ₹{{ number_format($retreat->price_per_person) }}
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <a href="{{ route('retreat.edit', $retreat) }}"
                            class="flex-1 px-3 py-2 bg-purple-50 text-purple-700 rounded-lg text-sm font-semibold hover:bg-purple-100 transition-colors">
                            Edit
                        </a>
                        <a href="{{ route('retreat.show', $retreat) }}"
                            class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors">
                            View
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $retreats->links() }}
    </div>
@endsection
