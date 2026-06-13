@extends('layouts.center')

@section('title', 'Accommodations — BalanceBoat')

@section('content')
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl font-serif font-bold text-slate-900">Accommodations</h1>
            <p class="text-slate-600 text-sm mt-1">Manage the accommodation options available at your center.</p>
        </div>
        <a href="{{ route('center-panel.accommodation.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-2xl text-xs font-semibold hover:bg-purple-700 transition-all inline-flex items-center gap-2 self-start lg:self-auto">
            <i class="fa-solid fa-plus"></i>
            Add Accommodation
        </a>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-4 flex items-center gap-3 text-sm text-emerald-700">
            <i class="fa-solid fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-rose-50 border border-rose-200 rounded-3xl p-4 flex items-center gap-3 text-sm text-rose-700">
            <i class="fa-solid fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="glass rounded-3xl shadow-sm overflow-hidden">
        @if($accommodations->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-regular fa-bed text-2xl text-slate-400"></i>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 mb-1">No accommodations yet</h3>
                <p class="text-xs text-slate-500 mb-4">Add accommodation options that guests can choose for their stay.</p>
                <a href="{{ route('center-panel.accommodation.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-2xl text-xs font-semibold hover:bg-purple-700 transition-all">
                    <i class="fa-solid fa-plus"></i>
                    Add First Accommodation
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50/80 border-b border-slate-200">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Name</th>
                            <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-[0.12em] text-slate-500 hidden md:table-cell">Slug</th>
                            <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-[0.12em] text-slate-500 hidden lg:table-cell">Max Guests</th>
                            <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-[0.12em] text-slate-500 hidden lg:table-cell">Banner</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($accommodations as $accommodation)
                        <tr class="hover:bg-slate-50/60 transition-colors" x-data="{ confirmDelete: false }">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900 text-sm">{{ $accommodation->name }}</p>
                                @if($accommodation->description)
                                    <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ Str::limit(strip_tags($accommodation->description), 60) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <span class="font-mono text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded-lg">{{ $accommodation->slug }}</span>
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                @if($accommodation->max_guest_in_room)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-700">
                                        <i class="fa-regular fa-user text-slate-400"></i>
                                        {{ $accommodation->max_guest_in_room }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                @if($accommodation->banner_image_url)
                                    <img src="{{ Storage::disk('azure')->url($accommodation->banner_image_url) }}"
                                         alt="{{ $accommodation->banner_image_title }}"
                                         class="w-16 h-10 object-cover rounded-xl border border-slate-200">
                                @else
                                    <span class="text-xs text-slate-400">No image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 justify-end">
                                    <a href="{{ route('center-panel.accommodation.edit', $accommodation->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-purple-700 text-xs font-semibold hover:bg-purple-100 transition-all">
                                        <i class="fa-regular fa-pen-to-square text-[11px]"></i> Edit
                                    </a>
                                    <button @click="confirmDelete = true"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 text-rose-600 text-xs font-semibold hover:bg-rose-100 transition-all">
                                        <i class="fa-regular fa-trash-can text-[11px]"></i> Delete
                                    </button>
                                </div>

                                {{-- Delete Confirmation Modal --}}
                                <div x-show="confirmDelete"
                                     x-cloak
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm"
                                     @keydown.escape.window="confirmDelete = false">
                                    <div class="bg-white rounded-3xl p-6 shadow-xl max-w-sm w-full mx-4" @click.stop>
                                        <div class="w-12 h-12 rounded-2xl bg-rose-100 flex items-center justify-center mx-auto mb-4">
                                            <i class="fa-solid fa-trash-can text-rose-500 text-lg"></i>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900 text-center mb-1">Delete Accommodation</h3>
                                        <p class="text-xs text-slate-500 text-center mb-6">
                                            Are you sure you want to delete <strong class="text-slate-700">{{ $accommodation->name }}</strong>? This action cannot be undone.
                                        </p>
                                        <div class="flex gap-3">
                                            <button @click="confirmDelete = false"
                                                    class="flex-1 py-2.5 rounded-2xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all">
                                                Cancel
                                            </button>
                                            <form action="{{ route('center-panel.accommodation.destroy') }}" method="POST" class="flex-1">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $accommodation->id }}">
                                                <button type="submit"
                                                        class="w-full py-2.5 rounded-2xl bg-rose-500 text-white text-xs font-semibold hover:bg-rose-600 transition-all">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
