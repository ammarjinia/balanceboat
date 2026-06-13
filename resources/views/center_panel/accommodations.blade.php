@extends('layouts.center')

@section('title', 'Accommodations — BalanceBoat')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-purple-500 mb-1">Center Management</p>
            <h1 class="text-3xl font-serif font-bold text-slate-900">Accommodations</h1>
            <p class="text-slate-500 text-sm mt-1">Manage accommodation options available at your center.</p>
        </div>
        <a href="{{ route('center-panel.accommodation.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white rounded-2xl text-xs font-semibold hover:bg-purple-700 active:scale-95 transition-all shadow-sm shadow-purple-200 self-start lg:self-auto">
            <i class="fa-solid fa-plus"></i>
            Add Accommodation
        </a>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-emerald-700">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-rose-50 border border-rose-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-rose-700">
            <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($accommodations->isEmpty())

        {{-- Empty State --}}
        <div class="glass rounded-3xl shadow-sm">
            <div class="py-20 flex flex-col items-center text-center px-6">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-purple-50 to-orange-50 border border-purple-100 flex items-center justify-center mb-5 shadow-sm">
                    <i class="fa-regular fa-bed text-3xl text-purple-400"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">No accommodations yet</h3>
                <p class="text-sm text-slate-500 max-w-xs mb-6">Add the room and accommodation options available at your retreat center.</p>
                <a href="{{ route('center-panel.accommodation.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white rounded-2xl text-xs font-semibold hover:bg-purple-700 transition-all shadow-sm shadow-purple-200">
                    <i class="fa-solid fa-plus"></i> Add First Accommodation
                </a>
            </div>
        </div>

    @else

        {{-- Stats Row --}}
        @php
            $totalCount  = $accommodations->count();
            $withImages  = $accommodations->filter(fn($a) => $a->banner_image_url)->count();
            $avgGuests   = $accommodations->whereNotNull('max_guest_in_room')->avg('max_guest_in_room');
        @endphp
        <div class="grid grid-cols-3 gap-4">
            <div class="glass rounded-3xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 flex items-center justify-center shrink-0">
                    <i class="fa-regular fa-bed text-purple-500 text-base"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900 leading-none">{{ $totalCount }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Total Accommodations</p>
                </div>
            </div>
            <div class="glass rounded-3xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center shrink-0">
                    <i class="fa-regular fa-image text-emerald-500 text-base"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900 leading-none">{{ $withImages }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">With Banner Image</p>
                </div>
            </div>
            <div class="glass rounded-3xl p-5 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-2xl bg-orange-50 flex items-center justify-center shrink-0">
                    <i class="fa-regular fa-user text-orange-400 text-base"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900 leading-none">{{ $avgGuests ? round($avgGuests) : '—' }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Avg. Max Guests</p>
                </div>
            </div>
        </div>

        {{-- Card Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($accommodations as $accommodation)
            <div class="glass rounded-3xl shadow-sm overflow-hidden flex flex-col card-hover" x-data="{ confirmDelete: false }">

                {{-- Banner Image --}}
                <div class="relative h-44 bg-gradient-to-br from-slate-100 to-slate-200 overflow-hidden shrink-0">
                    @if($accommodation->banner_image_url)
                        <img src="{{ Storage::disk('azure')->url($accommodation->banner_image_url) }}"
                             alt="{{ $accommodation->banner_image_title }}"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                            <i class="fa-regular fa-image text-4xl text-slate-300"></i>
                            <span class="text-xs text-slate-400">No image</span>
                        </div>
                    @endif

                    {{-- Max Guests badge overlaid on image --}}
                    @if($accommodation->max_guest_in_room)
                    <span class="absolute top-3 right-3 inline-flex items-center gap-1 bg-white/90 backdrop-blur-sm text-slate-700 text-[11px] font-semibold px-2.5 py-1 rounded-xl shadow-sm">
                        <i class="fa-solid fa-user text-purple-500 text-[9px]"></i>
                        {{ $accommodation->max_guest_in_room }} {{ $accommodation->max_guest_in_room == 1 ? 'guest' : 'guests' }}
                    </span>
                    @endif
                </div>

                {{-- Card Body --}}
                <div class="p-5 flex flex-col flex-1 gap-3">
                    <div class="space-y-1.5">
                        <h3 class="font-bold text-slate-900 text-sm leading-snug">{{ $accommodation->name }}</h3>
                        <span class="inline-block font-mono text-[10px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-lg">{{ $accommodation->slug }}</span>
                    </div>

                    @if($accommodation->description)
                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 flex-1">
                        {{ Str::limit(strip_tags($accommodation->description), 100) }}
                    </p>
                    @else
                    <p class="text-xs text-slate-300 italic flex-1">No description added.</p>
                    @endif

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
                        <a href="{{ route('center-panel.accommodation.edit', $accommodation->id) }}"
                           class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl bg-purple-50 text-purple-700 text-xs font-semibold hover:bg-purple-100 transition-all">
                            <i class="fa-regular fa-pen-to-square text-[11px]"></i> Edit
                        </a>
                        <button type="button"
                                @click="confirmDelete = true"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 rounded-xl bg-rose-50 text-rose-600 text-xs font-semibold hover:bg-rose-100 transition-all">
                            <i class="fa-regular fa-trash-can text-[11px]"></i> Delete
                        </button>
                    </div>
                </div>

                {{-- Delete Confirmation Modal --}}
                <div x-show="confirmDelete"
                     x-cloak
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm p-4"
                     @keydown.escape.window="confirmDelete = false">
                    <div class="bg-white rounded-3xl p-7 shadow-2xl max-w-sm w-full" @click.stop>
                        <div class="w-14 h-14 rounded-2xl bg-rose-100 flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-trash-can text-rose-500 text-xl"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 text-center mb-2">Delete Accommodation?</h3>
                        <p class="text-xs text-slate-500 text-center mb-6 leading-relaxed">
                            You're about to permanently delete<br>
                            <strong class="text-slate-700">{{ $accommodation->name }}</strong>.<br>
                            This action cannot be undone.
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
                                    Yes, Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach

            {{-- Add New Card --}}
            <a href="{{ route('center-panel.accommodation.create') }}"
               class="rounded-3xl border-2 border-dashed border-slate-200 hover:border-purple-300 hover:bg-purple-50/20 transition-all flex flex-col items-center justify-center gap-3 min-h-[280px] group">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 group-hover:bg-purple-100 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-plus text-slate-400 group-hover:text-purple-500 transition-colors"></i>
                </div>
                <span class="text-xs font-semibold text-slate-400 group-hover:text-purple-600 transition-colors">Add Accommodation</span>
            </a>
        </div>

    @endif

@endsection
