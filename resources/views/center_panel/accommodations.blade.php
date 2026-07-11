@extends('layouts.center')

@section('title', 'Accommodation Management — BalanceBoat')

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
<style>
    .bb-serif { font-family: 'Playfair Display', serif; }
    .bb-sans  { font-family: 'Outfit', sans-serif; }
    .glass-premium {
        background: rgba(255,255,255,0.75);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.7);
        box-shadow: 0 4px 24px -6px rgba(15,23,42,0.08);
    }
    .card-accom {
        background: #fff;
        border: 1px solid rgba(47,111,87,0.1);
        border-radius: 24px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-accom:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 50px -15px rgba(47,111,87,0.2);
    }
    .premium-badge {
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    input[type="text"]:focus,
    select:focus {
        outline: none;
        border-color: #2F6F57;
        box-shadow: 0 0 0 3px rgba(47,111,87,0.1);
    }
</style>
@endsection

@section('content')

@php
    $totalCount   = $accommodations->count();
    $withBanner   = $accommodations->filter(fn($a) => $a->banner_image_url)->count();
    $avgGuests    = $accommodations->whereNotNull('max_guest_in_room')->avg('max_guest_in_room');
    $totalCap     = $accommodations->sum('max_guest_in_room');
@endphp

    {{-- ── Page Header ─────────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 border-b border-[#2F6F57]/10 pb-6 bb-sans">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="text-[10px] bg-[#D4AF37]/20 text-[#B8960E] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg">
                    Inventory Management
                </span>
            </div>
            <h1 class="bb-serif text-3xl font-medium text-[#1E2522] tracking-tight">Accommodation Management</h1>
            <p class="text-[#64748B] text-sm mt-1 font-light">Configure room categories, descriptions, media, and guest capacity limits.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('center-panel.accommodation.create') }}"
               class="bb-sans inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#2F6F57] text-white text-xs font-semibold hover:bg-[#255a46] transition-all shadow-sm shadow-[#2F6F57]/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Accommodation
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-emerald-700 bb-sans">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-rose-50 border border-rose-200 rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-rose-700 bb-sans">
            <i class="fa-solid fa-circle-exclamation text-rose-500"></i> {{ session('error') }}
        </div>
    @endif

    @if($accommodations->isEmpty())

        {{-- ── Empty State ─────────────────────────────────────────── --}}
        <div class="glass-premium rounded-3xl py-20 flex flex-col items-center text-center px-6 bb-sans">
            <div class="w-20 h-20 rounded-3xl bg-[#2F6F57]/5 border border-[#2F6F57]/15 flex items-center justify-center mb-5">
                <i class="fa-regular fa-bed text-3xl text-[#2F6F57]/50"></i>
            </div>
            <h3 class="bb-serif text-xl font-medium text-[#1E2522] mb-1">No accommodations yet</h3>
            <p class="text-sm text-[#64748B] max-w-xs mb-6 font-light">Add room categories and accommodation options available at your retreat center.</p>
            <a href="{{ route('center-panel.accommodation.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2F6F57] text-white rounded-xl text-xs font-semibold hover:bg-[#255a46] transition-all shadow-sm shadow-[#2F6F57]/20">
                <i class="fa-solid fa-plus"></i> Add First Accommodation
            </a>
        </div>

    @else

        {{-- ── Stats Row ────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 bb-sans">
            <div class="glass-premium p-5 rounded-2xl">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Active Units</span>
                <strong class="bb-serif text-2xl font-semibold text-[#1E2522] block mt-1">{{ $totalCount }} {{ $totalCount == 1 ? 'Room' : 'Rooms' }}</strong>
                <div class="text-[11px] text-[#2F6F57] font-medium mt-1">All available categories</div>
            </div>
            <div class="glass-premium p-5 rounded-2xl">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Media Coverage</span>
                <strong class="bb-serif text-2xl font-semibold text-[#1E2522] block mt-1">{{ $withBanner }}/{{ $totalCount }}</strong>
                <div class="text-[11px] text-[#64748B] font-medium mt-1">Have banner images</div>
            </div>
            <div class="glass-premium p-5 rounded-2xl">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Avg. Capacity</span>
                <strong class="bb-serif text-2xl font-semibold text-[#1E2522] block mt-1">{{ $avgGuests ? round($avgGuests) : '—' }} Guests</strong>
                <div class="text-[11px] text-[#64748B] font-medium mt-1">Per accommodation unit</div>
            </div>
            <div class="glass-premium p-5 rounded-2xl">
                <span class="text-[10px] font-bold text-[#64748B] uppercase tracking-wider block">Total Capacity</span>
                <strong class="bb-serif text-2xl font-semibold text-[#1E2522] block mt-1">{{ $totalCap ?: '—' }}</strong>
                <div class="text-[11px] text-[#D4AF37] font-bold uppercase tracking-wide mt-1">✦ Combined Guest Limit</div>
            </div>
        </div>

        {{-- ── Search & Filter Bar ──────────────────────────────────── --}}
        <div class="glass-premium rounded-2xl p-4 flex flex-col md:flex-row items-center gap-4 bb-sans">
            <div class="w-full md:max-w-sm relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#64748B]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" id="search-input" onkeyup="filterGrid()"
                       placeholder="Search by accommodation name..."
                       class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl border border-[#2F6F57]/15 bg-white/90 placeholder-[#64748B]/50 transition-all">
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto">
                <select id="capacity-filter" onchange="filterGrid()"
                        class="px-3 py-2.5 text-xs rounded-xl border border-[#2F6F57]/15 bg-white text-[#1E2522] transition-all">
                    <option value="ALL">All Capacities</option>
                    <option value="1">1 Guest</option>
                    <option value="2">2 Guests</option>
                    <option value="3-4">3–4 Guests</option>
                    <option value="5+">5+ Guests</option>
                </select>
                <button onclick="resetFilters()" class="text-xs font-semibold text-[#64748B] hover:text-[#1E2522] transition-colors px-2 py-1 whitespace-nowrap">
                    Clear Filters
                </button>
            </div>
        </div>

        {{-- ── Card Grid ────────────────────────────────────────────── --}}
        <div id="accommodation-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @foreach($accommodations as $accommodation)
            @php
                $bannerUrl  = $accommodation->banner_image_url ? Storage::disk('s3')->url($accommodation->banner_image_url) : null;
                $descPlain  = Str::limit(strip_tags($accommodation->description ?? ''), 80);
                $capacity   = $accommodation->max_guest_in_room ?? 0;
            @endphp

            <article class="card-accom flex flex-col"
                     data-name="{{ strtolower($accommodation->name) }}"
                     data-capacity="{{ $capacity }}"
                     data-id="{{ $accommodation->id }}"
                     data-desc="{{ $descPlain }}"
                     data-image="{{ $bannerUrl ?? '' }}"
                     data-full-name="{{ $accommodation->name }}"
                     data-slug="{{ $accommodation->slug }}">

                {{-- Image Area --}}
                <div class="relative h-44 bg-gradient-to-br from-[#F8FAF8] to-[#e2ebe6] overflow-hidden shrink-0">
                    @if($bannerUrl)
                        <img src="{{ $bannerUrl }}" alt="{{ $accommodation->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1E2522]/35 to-transparent"></div>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                            <i class="fa-regular fa-image text-4xl text-[#2F6F57]/20"></i>
                        </div>
                    @endif

                    {{-- Capacity badge (top-right) --}}
                    @if($capacity)
                    <span class="absolute top-3 right-3 premium-badge bg-white/85 text-[#1E2522] text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                        👥 {{ $capacity }} {{ $capacity == 1 ? 'Guest' : 'Guests' }}
                    </span>
                    @endif

                    {{-- Slug label (bottom-left) --}}
                    @if($accommodation->slug)
                    <span class="absolute bottom-3 left-3 premium-badge bg-[#1E2522]/60 text-white text-[10px] font-mono px-2.5 py-0.5 rounded-md">
                        {{ $accommodation->slug }}
                    </span>
                    @endif
                </div>

                {{-- Card Body --}}
                <div class="p-4 space-y-2 flex-1 flex flex-col">
                    <div class="flex-1">
                        <h3 class="bb-serif text-base font-medium text-[#1E2522] tracking-tight line-clamp-1">
                            {{ $accommodation->name }}
                        </h3>
                        <p class="text-[11px] text-[#64748B] line-clamp-2 mt-0.5 font-light leading-relaxed">
                            {{ $descPlain ?: 'No description added yet.' }}
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-3 border-t border-[#2F6F57]/8 flex items-center justify-between mt-1">
                        <div></div>
                        <div class="flex items-center gap-1 text-[10px] font-bold uppercase tracking-wide bb-sans">
                            <button onclick="showPreviewModal(this.closest('article'))"
                                    class="text-[#2F6F57] hover:underline px-2 py-1 transition-colors cursor-pointer">
                                Preview
                            </button>
                            <span class="text-[#64748B]/30">|</span>
                            <a href="{{ route('center-panel.accommodation.edit', $accommodation->id) }}"
                               class="text-[#1E2522] hover:underline px-2 py-1 transition-colors">
                                Edit
                            </a>
                            <span class="text-[#64748B]/30">|</span>
                            <button onclick="showDeleteModal('{{ $accommodation->id }}', '{{ addslashes($accommodation->name) }}')"
                                    class="text-rose-600 hover:underline px-2 py-1 transition-colors cursor-pointer">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach

            {{-- Add New Card --}}
            <a href="{{ route('center-panel.accommodation.create') }}"
               class="rounded-3xl border-2 border-dashed border-[#2F6F57]/20 hover:border-[#2F6F57]/40 hover:bg-[#2F6F57]/5 transition-all flex flex-col items-center justify-center gap-3 min-h-[280px] group cursor-pointer">
                <div class="w-12 h-12 rounded-2xl bg-[#2F6F57]/8 group-hover:bg-[#2F6F57]/15 flex items-center justify-center transition-all">
                    <i class="fa-solid fa-plus text-[#2F6F57]/50 group-hover:text-[#2F6F57] transition-colors text-sm"></i>
                </div>
                <span class="text-xs font-semibold text-[#64748B] group-hover:text-[#2F6F57] transition-colors bb-sans">Add Accommodation</span>
            </a>
        </div>

        {{-- Empty filter result --}}
        <div id="grid-empty-state" class="hidden glass-premium p-16 text-center rounded-3xl border-2 border-dashed border-[#8BA888]/30 space-y-4 max-w-xl mx-auto bb-sans">
            <div class="w-16 h-16 bg-[#2F6F57]/5 rounded-full flex items-center justify-center text-2xl mx-auto">🏨</div>
            <div class="space-y-1">
                <h3 class="bb-serif text-xl font-medium text-[#1E2522]">No matches found</h3>
                <p class="text-xs text-[#64748B] max-w-sm mx-auto font-light">Try adjusting your search terms or clear the active filters.</p>
            </div>
            <button onclick="resetFilters()" class="px-5 py-2.5 rounded-xl bg-[#2F6F57] text-white text-xs font-semibold hover:bg-[#255a46] transition-all">
                Clear Filters
            </button>
        </div>

    @endif

    {{-- ── Preview Modal ────────────────────────────────────────────── --}}
    <div id="preview-modal" class="fixed inset-0 z-50 bg-[#1E2522]/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-[#2F6F57]/10">
            <div class="h-52 bg-gradient-to-br from-[#2F6F57]/10 to-[#8BA888]/20 relative overflow-hidden">
                <img id="pm-image" src="" alt="" class="w-full h-full object-cover hidden">
                <div id="pm-no-image" class="w-full h-full flex items-center justify-center">
                    <i class="fa-regular fa-bed text-5xl text-[#2F6F57]/25"></i>
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#1E2522]/40 via-transparent to-transparent"></div>
                <button onclick="hidePreviewModal()" class="absolute top-4 right-4 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center text-[#1E2522] hover:bg-white transition-all shadow-sm text-xs font-bold">
                    ✕
                </button>
                <span id="pm-capacity-badge" class="absolute top-4 left-4 bg-white/90 text-[#1E2522] text-[10px] font-bold px-2.5 py-1 rounded-full hidden"></span>
            </div>
            <div class="p-6 space-y-3 bb-sans">
                <div>
                    <span class="text-[10px] text-[#2F6F57] font-bold uppercase tracking-wider">Accommodation</span>
                    <h3 id="pm-name" class="bb-serif text-2xl font-medium text-[#1E2522] tracking-tight mt-0.5"></h3>
                    <span id="pm-slug" class="text-[10px] font-mono text-[#64748B] bg-slate-100 px-2 py-0.5 rounded-md mt-1 inline-block"></span>
                </div>
                <p id="pm-desc" class="text-xs text-[#64748B] leading-relaxed font-light"></p>
                <div class="pt-3 border-t border-[#2F6F57]/8 flex gap-3">
                    <a id="pm-edit-link" href="#"
                       class="flex-1 py-2.5 rounded-xl bg-[#2F6F57] text-white text-xs font-semibold hover:bg-[#255a46] transition-all text-center">
                        Edit Accommodation
                    </a>
                    <button onclick="hidePreviewModal()"
                            class="flex-1 py-2.5 rounded-xl border border-[#2F6F57]/20 text-[#64748B] text-xs font-semibold hover:bg-slate-50 transition-all">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Delete Modal ─────────────────────────────────────────────── --}}
    <div id="delete-modal" class="fixed inset-0 z-50 bg-[#1E2522]/40 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-rose-100 space-y-4 bb-sans">
            <div class="w-12 h-12 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center text-xl mx-auto">⚠️</div>
            <div class="text-center space-y-1">
                <h3 class="bb-serif text-xl font-medium text-[#1E2522]">Delete Accommodation?</h3>
                <p class="text-xs text-[#64748B] font-light">You are about to permanently remove</p>
                <p class="text-sm font-semibold text-[#1E2522]" id="delete-modal-name"></p>
                <p class="text-[11px] text-[#64748B] font-light">This action removes the link from your center and cannot be undone.</p>
            </div>
            <div class="grid grid-cols-2 gap-3 pt-1">
                <button onclick="hideDeleteModal()"
                        class="py-2.5 rounded-xl border border-[#2F6F57]/20 text-xs font-semibold text-[#64748B] hover:bg-slate-50 transition-all">
                    Cancel
                </button>
                <form id="delete-form" action="{{ route('center-panel.accommodation.destroy') }}" method="POST">
                    @csrf
                    <input type="hidden" id="delete-form-id" name="id" value="">
                    <button type="submit"
                            class="w-full py-2.5 rounded-xl bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700 transition-all shadow-sm">
                        Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
(function () {

    /* ── Search & Capacity Filter ────────────────────────────────────── */
    window.filterGrid = function () {
        const search   = document.getElementById('search-input').value.toLowerCase().trim();
        const capacity = document.getElementById('capacity-filter').value;
        const cards    = document.querySelectorAll('#accommodation-grid article[data-id]');
        let visible = 0;

        cards.forEach(card => {
            const name = card.dataset.name || '';
            const cap  = parseInt(card.dataset.capacity) || 0;

            const matchSearch = !search || name.includes(search);
            let matchCap = true;
            if (capacity === '1')   matchCap = cap === 1;
            if (capacity === '2')   matchCap = cap === 2;
            if (capacity === '3-4') matchCap = cap === 3 || cap === 4;
            if (capacity === '5+')  matchCap = cap >= 5;

            if (matchSearch && matchCap) { card.classList.remove('hidden'); visible++; }
            else                         { card.classList.add('hidden'); }
        });

        const empty = document.getElementById('grid-empty-state');
        if (empty) empty.classList.toggle('hidden', visible > 0);
    };

    window.resetFilters = function () {
        document.getElementById('search-input').value  = '';
        document.getElementById('capacity-filter').value = 'ALL';
        filterGrid();
    };

    /* ── Preview Modal ───────────────────────────────────────────────── */
    window.showPreviewModal = function (card) {
        const image    = card.dataset.image;
        const name     = card.dataset.fullName;
        const slug     = card.dataset.slug;
        const desc     = card.dataset.desc;
        const capacity = card.dataset.capacity;
        const id       = card.dataset.id;

        const pmImage  = document.getElementById('pm-image');
        const pmNoImg  = document.getElementById('pm-no-image');
        const pmCap    = document.getElementById('pm-capacity-badge');

        document.getElementById('pm-name').textContent = name;
        document.getElementById('pm-slug').textContent = slug;
        document.getElementById('pm-desc').textContent = desc || 'No description added yet.';
        document.getElementById('pm-edit-link').href  = '/center-panel/accommodations/' + id + '/edit';

        if (image) {
            pmImage.src = image;
            pmImage.classList.remove('hidden');
            pmNoImg.classList.add('hidden');
        } else {
            pmImage.classList.add('hidden');
            pmNoImg.classList.remove('hidden');
        }

        if (capacity && capacity > 0) {
            pmCap.textContent = '👥 ' + capacity + (capacity == 1 ? ' Guest' : ' Guests');
            pmCap.classList.remove('hidden');
        } else {
            pmCap.classList.add('hidden');
        }

        document.getElementById('preview-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.hidePreviewModal = function () {
        document.getElementById('preview-modal').classList.add('hidden');
        document.body.style.overflow = '';
    };

    /* ── Delete Modal ────────────────────────────────────────────────── */
    window.showDeleteModal = function (id, name) {
        document.getElementById('delete-modal-name').textContent = name;
        document.getElementById('delete-form-id').value = id;
        document.getElementById('delete-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.hideDeleteModal = function () {
        document.getElementById('delete-modal').classList.add('hidden');
        document.body.style.overflow = '';
    };

    /* Close modals on backdrop click */
    document.getElementById('preview-modal')?.addEventListener('click', function (e) {
        if (e.target === this) hidePreviewModal();
    });
    document.getElementById('delete-modal')?.addEventListener('click', function (e) {
        if (e.target === this) hideDeleteModal();
    });

    /* Close on Escape */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { hidePreviewModal(); hideDeleteModal(); }
    });

})();
</script>
@endsection
