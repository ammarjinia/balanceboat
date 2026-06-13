@extends('layouts.center')

@section('title', ($accommodation ? 'Edit' : 'Add') . ' Accommodation — BalanceBoat')

@section('head')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" rel="stylesheet">
<style>
    .dropzone-custom {
        border: 2px dashed #e2e8f0;
        border-radius: 20px;
        background: #f8fafc;
        min-height: 130px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        padding: 16px;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
    }
    .dropzone-custom.dz-drag-hover {
        border-color: #a855f7;
        background: #faf5ff;
    }
    .dropzone-custom .dz-message {
        color: #94a3b8;
        font-size: 13px;
        text-align: center;
        margin: 0;
        pointer-events: none;
    }
    .dropzone-custom .dz-preview .dz-image img { border-radius: 10px; }
    .dropzone-custom .dz-preview .dz-remove {
        font-size: 11px;
        color: #f43f5e;
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: 4px;
    }
</style>
@endsection

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl font-serif font-bold text-slate-900">{{ $pageTitle }}</h1>
            <p class="text-slate-600 text-sm mt-1">
                @if($accommodation)
                    Update the details, description, and images for this accommodation.
                @else
                    Fill in the details below to add a new accommodation option for your center.
                @endif
            </p>
        </div>
        <a href="{{ route('center-panel.accommodations') }}"
           class="px-4 py-2 bg-slate-100 text-slate-700 rounded-2xl text-xs font-semibold hover:bg-slate-200 transition-all inline-flex items-center gap-2 self-start lg:self-auto">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Accommodations
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-3xl p-4 text-sm text-rose-700 space-y-1">
            @foreach ($errors->all() as $error)
                <p class="flex items-center gap-2"><i class="fa-solid fa-exclamation-circle text-xs"></i>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form id="frmAccommodation"
          action="{{ $accommodation ? route('center-panel.accommodation.update', $accommodation->id) : route('center-panel.accommodation.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Left Column: Core Fields --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Name & Slug --}}
                <div class="glass rounded-3xl p-6 shadow-sm space-y-5">
                    <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-regular fa-tag text-purple-500"></i> Basic Details
                    </h2>

                    <div class="space-y-2">
                        <label for="name" class="block text-xs font-semibold text-slate-700">
                            Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                               value="{{ old('name', $accommodation->name ?? '') }}"
                               required
                               class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition-all"
                               placeholder="e.g. Deluxe Private Room">
                        <p class="text-[11px] text-slate-400">The name as it will appear on the listing.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="slug" class="block text-xs font-semibold text-slate-700">
                            Slug <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="slug" name="slug"
                               value="{{ old('slug', $accommodation->slug ?? '') }}"
                               required
                               class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 text-sm font-mono focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition-all"
                               placeholder="deluxe-private-room">
                        <p class="text-[11px] text-slate-400">URL-friendly identifier — auto-generated from name. Lowercase letters, numbers, and hyphens only.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="max_guest_in_room" class="block text-xs font-semibold text-slate-700">Max Guests</label>
                        <select id="max_guest_in_room" name="max_guest_in_room"
                                class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition-all">
                            <option value="">Select capacity</option>
                            @for ($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}" {{ old('max_guest_in_room', $accommodation->max_guest_in_room ?? '') == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i === 1 ? 'guest' : 'guests' }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Description --}}
                <div class="glass rounded-3xl p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-regular fa-file-lines text-purple-500"></i> Description
                    </h2>
                    <textarea id="description" name="description" rows="6"
                              class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition-all resize-none"
                              placeholder="Describe this accommodation — amenities, layout, views, and what makes it special...">{{ old('description', $accommodation->description ?? '') }}</textarea>
                </div>

                {{-- Gallery Images --}}
                <div class="glass rounded-3xl p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-regular fa-images text-purple-500"></i> Image Gallery
                    </h2>
                    <p class="text-xs text-slate-500">Upload multiple photos showing the accommodation from different angles.</p>

                    <div id="image_gallery" class="dropzone-custom">
                        <div class="dz-message">
                            <i class="fa-regular fa-cloud-arrow-up text-2xl text-slate-300 block mb-2"></i>
                            <span class="font-medium text-slate-500">Click or drag images here to upload</span><br>
                            <span class="text-xs text-slate-400">PNG, JPG, WEBP supported</span>
                        </div>
                    </div>
                    <input type="hidden" id="image_gallery_ids" name="image_gallery_ids" value="">
                    <input type="hidden" id="dropzone_url" value="{{ route('center-panel.accommodation.upload_gallery_image') }}">

                    {{-- Existing gallery images --}}
                    @if(!empty($imagegalleries) && count($imagegalleries) > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 pt-2" id="existing_gallery">
                        @foreach($imagegalleries as $gallery)
                        <div id="gallery-img-{{ $gallery->id }}" class="relative group rounded-2xl overflow-hidden border border-slate-200">
                            <img src="{{ Storage::disk('azure')->url($gallery->image_url) }}"
                                 alt="{{ $gallery->image_title }}"
                                 class="w-full h-24 object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                <button type="button"
                                        class="btn-delete-gallery text-white text-xs bg-rose-500 hover:bg-rose-600 px-3 py-1.5 rounded-xl font-semibold transition-all"
                                        data-id="{{ $gallery->id }}"
                                        data-url="{{ route('center-panel.accommodation.delete_gallery_image') }}">
                                    <i class="fa-solid fa-trash-can text-[10px] mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

            </div>

            {{-- Right Column: Banner Image & Actions --}}
            <div class="space-y-5">

                {{-- Banner Image --}}
                <div class="glass rounded-3xl p-6 shadow-sm space-y-4">
                    <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-regular fa-image text-purple-500"></i> Banner Image
                    </h2>

                    @if($accommodation && $accommodation->banner_image_url)
                        <div id="banner_img_container" class="relative rounded-2xl overflow-hidden border border-slate-200">
                            <img id="banner_current"
                                 src="{{ Storage::disk('azure')->url($accommodation->banner_image_url) }}"
                                 alt="{{ $accommodation->banner_image_title }}"
                                 class="w-full h-40 object-cover">
                            <div class="p-3 flex items-center justify-between bg-slate-50">
                                <span class="text-[11px] text-slate-500 truncate">{{ $accommodation->banner_image_title }}</span>
                                <button type="button"
                                        id="btn_delete_banner"
                                        data-id="{{ $accommodation->id }}"
                                        data-url="{{ route('center-panel.accommodation.delete_banner_image') }}"
                                        class="text-rose-500 hover:text-rose-700 text-xs font-semibold transition-all shrink-0 ml-2">
                                    <i class="fa-solid fa-trash-can text-[10px] mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                    @else
                        <div id="banner_preview_wrap" class="hidden rounded-2xl overflow-hidden border border-slate-200 mb-3">
                            <img id="banner_preview" src="" alt="Preview" class="w-full h-40 object-cover">
                        </div>
                    @endif

                    <label class="block w-full cursor-pointer">
                        <div class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-slate-200 rounded-2xl p-6 hover:border-purple-400 hover:bg-purple-50/30 transition-all text-center">
                            <i class="fa-regular fa-cloud-arrow-up text-xl text-slate-300"></i>
                            <span class="text-xs font-medium text-slate-500">
                                {{ ($accommodation && $accommodation->banner_image_url) ? 'Replace image' : 'Upload banner image' }}
                            </span>
                            <span class="text-[11px] text-slate-400">PNG, JPG, WEBP</span>
                        </div>
                        <input type="file" name="banner_image" id="banner_image" accept="image/*" class="sr-only">
                    </label>
                </div>

                {{-- Submit Card --}}
                <div class="glass rounded-3xl p-6 shadow-sm space-y-3">
                    <button type="submit"
                            class="w-full py-3 rounded-2xl bg-purple-600 text-white text-sm font-semibold hover:bg-purple-700 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-{{ $accommodation ? 'floppy-disk' : 'plus' }}"></i>
                        {{ $accommodation ? 'Save Changes' : 'Create Accommodation' }}
                    </button>
                    <a href="{{ route('center-panel.accommodations') }}"
                       class="w-full py-3 rounded-2xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                        Cancel
                    </a>
                </div>

                @if($accommodation)
                <div class="glass rounded-3xl p-5 shadow-sm" x-data="{ confirmDelete: false }">
                    <h3 class="text-xs font-bold text-rose-600 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation"></i> Danger Zone
                    </h3>
                    <button type="button"
                            @click="confirmDelete = true"
                            class="w-full py-2.5 rounded-2xl border border-rose-200 text-rose-600 text-xs font-semibold hover:bg-rose-50 transition-all">
                        <i class="fa-regular fa-trash-can mr-1"></i> Delete Accommodation
                    </button>

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
                                Are you sure you want to delete <strong class="text-slate-700">{{ $accommodation->name }}</strong>? This cannot be undone.
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
                </div>
                @endif

            </div>
        </div>
    </form>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script>
(function () {
    'use strict';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ── Name → Slug auto-generate ──────────────────────────────
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    nameInput?.addEventListener('blur', function () {
        if (!slugInput.value) {
            slugInput.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-|-$/g, '');
        }
    });

    // ── Banner image local preview ─────────────────────────────
    document.getElementById('banner_image')?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const wrap = document.getElementById('banner_preview_wrap');
            const img  = document.getElementById('banner_preview');
            if (wrap && img) {
                img.src = e.target.result;
                wrap.classList.remove('hidden');
            }
        };
        reader.readAsDataURL(file);
    });

    // ── Delete banner image ────────────────────────────────────
    document.getElementById('btn_delete_banner')?.addEventListener('click', async function (e) {
        e.preventDefault();
        if (!confirm('Remove this banner image?')) return;

        this.disabled = true;
        try {
            const res  = await fetch(this.dataset.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ id: this.dataset.id }),
            });
            const text = await res.text();
            if (text === '1') {
                document.getElementById('banner_img_container')?.remove();
            }
        } finally {
            this.disabled = false;
        }
    });

    // ── Delete existing gallery image ──────────────────────────
    document.querySelectorAll('.btn-delete-gallery').forEach(function (btn) {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            if (!confirm('Remove this image?')) return;

            this.disabled = true;
            try {
                const res  = await fetch(this.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ id: this.dataset.id }),
                });
                const text = await res.text();
                if (text === '1') {
                    document.getElementById('gallery-img-' + this.dataset.id)?.remove();
                }
            } finally {
                this.disabled = false;
            }
        });
    });

    // ── Dropzone gallery upload ────────────────────────────────
    Dropzone.autoDiscover = false;

    const dzEl = document.getElementById('image_gallery');
    if (dzEl) {
        const myDropzone = new Dropzone(dzEl, {
            url: document.getElementById('dropzone_url').value,
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
            dictRemoveFile: 'Remove',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            init: function () {
                this.on('success', function (file, response) {
                    if (response && response.success) {
                        const el   = document.getElementById('image_gallery_ids');
                        const prev = el.value;
                        el.value   = prev ? prev + '|@|@|' + response.filename : response.filename;
                    }
                });
                this.on('removedfile', function (file) {
                    if (!file.xhr) return;
                    try {
                        const response = JSON.parse(file.xhr.responseText);
                        const el       = document.getElementById('image_gallery_ids');
                        el.value       = el.value
                            .replace('|@|@|' + response.filename, '')
                            .replace(response.filename, '');
                    } catch (_) {}
                });
                this.on('error', function (file, msg) {
                    console.error('Upload error:', msg);
                });
            },
        });
    }
})();
</script>
@endsection
