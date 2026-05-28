@extends('layouts.center_dashboard')

@section('main-content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Create Retreat</h1>
            <p class="text-gray-600 mt-1">Set up a new wellness retreat program</p>
        </div>

        <form action="{{ route('retreat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- Basic Information --}}
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Basic Information</h2>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Retreat Name *
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                            placeholder="e.g., 7-Day Yoga Retreat">
                        @error('name')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Category *
                        </label>
                        <select name="experience_category"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition">
                            <option value="">Select Category</option>
                            <option value="yoga">Yoga</option>
                            <option value="meditation">Meditation</option>
                            <option value="wellness">Wellness</option>
                            <option value="detox">Detox</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Summary *
                    </label>
                    <textarea name="experience_summary" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition"
                        placeholder="Describe your retreat...">{{ old('experience_summary') }}</textarea>
                    @error('experience_summary')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Banner Image
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                        <input type="file" name="banner_image" accept="image/*" class="w-full">
                        <p class="text-gray-500 text-sm mt-2">JPEG, PNG up to 2MB</p>
                    </div>
                </div>
            </div>

            {{-- Dates & Pricing --}}
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Dates & Pricing</h2>

                <div class="grid grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Start Date *
                        </label>
                        <input type="datetime-local" name="start_date_time"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            End Date *
                        </label>
                        <input type="datetime-local" name="end_date_time"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Duration *
                        </label>
                        <select name="duration" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            <option value="3_days">3 Days</option>
                            <option value="5_days">5 Days</option>
                            <option value="7_days" selected>7 Days</option>
                            <option value="10_days">10 Days</option>
                            <option value="14_days">14 Days</option>
                            <option value="21_days">21 Days</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Price per Person (₹) *
                        </label>
                        <input type="number" name="price_per_person" step="0.01"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="10000">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Currency *
                        </label>
                        <select name="currency" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            <option value="INR" selected>INR (₹)</option>
                            <option value="USD">USD ($)</option>
                            <option value="EUR">EUR (€)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Early Bird Discount (%)
                        </label>
                        <input type="number" name="early_bird_discount" step="0.01" min="0" max="100"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            {{-- Accommodations --}}
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Accommodations *</h2>

                <div class="space-y-3">
                    @forelse($accommodations as $accommodation)
                        <label
                            class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-purple-50 transition">
                            <input type="checkbox" name="accommodations[]" value="{{ $accommodation->id }}" class="rounded">
                            <span class="ml-3 font-medium text-gray-900">
                                {{ $accommodation->name }}
                            </span>
                        </label>
                    @empty
                        <p class="text-gray-600 text-sm">No accommodations available</p>
                    @endforelse
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex gap-4">
                <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-lg hover:shadow-lg transition-shadow">
                    Create Retreat
                </button>
                <a href="{{ route('retreat.index') }}"
                    class="px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
