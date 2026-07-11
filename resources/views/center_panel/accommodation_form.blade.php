@extends('layouts.center')

@section('title', ($accommodation ? 'Edit' : 'Add') . ' Accommodation — BalanceBoat')

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" rel="stylesheet">
<style>
    .bb-serif { font-family: 'Playfair Display', serif; }
    .bb-sans  { font-family: 'Outfit', sans-serif; }

    .glass-premium {
        background: rgba(255,255,255,0.80);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.75);
        box-shadow: 0 4px 24px -6px rgba(15,23,42,0.07);
    }

    .premium-input {
        background: rgba(255,255,255,0.9);
        border: 1px solid rgba(47,111,87,0.18);
        border-radius: 14px;
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 10px 16px;
        font-size: 13px;
        width: 100%;
    }
    .premium-input:focus {
        outline: none;
        border-color: #2F6F57;
        box-shadow: 0 0 0 3px rgba(47,111,87,0.12);
    }

    /* Wizard Tabs */
    .wizard-tab {
        padding: 8px 0;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        transition: all 0.2s ease;
        cursor: pointer;
        color: #64748B;
        background: transparent;
        border: none;
    }
    .wizard-tab:hover { color: #1E2522; }
    .wizard-tab.active {
        background: #fff;
        color: #2F6F57;
        box-shadow: 0 1px 6px -1px rgba(47,111,87,0.15);
    }

    /* Dropzone */
    .dropzone-premium {
        border: 2px dashed rgba(47,111,87,0.25);
        border-radius: 18px;
        background: rgba(47,111,87,0.03);
        min-height: 120px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        padding: 20px;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
    }
    .dropzone-premium:hover,
    .dropzone-premium.dz-drag-hover {
        border-color: #2F6F57;
        background: rgba(47,111,87,0.06);
    }
    .dropzone-premium .dz-message { color: #64748B; font-size: 12px; text-align: center; margin: 0; }
    .dropzone-premium .dz-preview .dz-image img { border-radius: 10px; }
    .dropzone-premium .dz-preview .dz-remove { font-size: 11px; color: #f43f5e; text-decoration: none; display: block; text-align: center; margin-top: 4px; }

    /* TinyMCE overrides */
    .mce-tinymce, .mce-panel { border-radius: 14px !important; overflow: hidden; }
    .mce-toolbar-grp { background: #F8FAF8 !important; border-bottom: 1px solid rgba(47,111,87,0.1) !important; }
    .mce-edit-area { border: none !important; }
    .mce-statusbar { background: #F8FAF8 !important; border-top: 1px solid rgba(47,111,87,0.1) !important; font-size: 10px !important; }

    /* Preview Card */
    #live-preview-card {
        transition: all 0.3s ease;
    }
    .preview-image-placeholder {
        background: linear-gradient(135deg, rgba(47,111,87,0.08) 0%, rgba(139,168,136,0.15) 100%);
    }
</style>
@endsection

@section('content')

    {{-- ── Page Header ─────────────────────────────────────────────── --}}
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 border-b border-[#2F6F57]/10 pb-6 bb-sans">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span id="form-intent-badge"
                      class="text-[10px] bg-[#D4AF37]/20 text-[#B8960E] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg">
                    {{ $accommodation ? 'Studio Management Mode' : 'Creator Engine Mode' }}
                </span>
            </div>
            <h2 class="bb-serif text-3xl font-medium text-[#1E2522]">{{ $pageTitle }}</h2>
            <p class="text-[#64748B] text-xs mt-0.5 font-light">
                {{ $accommodation ? 'Update accommodation details, media, and descriptions.' : 'Map physical layout, descriptions, and media assets for your new accommodation category.' }}
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            @if($accommodation)
                <span class="text-xs text-[#64748B] font-light hidden lg:block" id="last-saved-label">Configuration last saved</span>
            @endif
            <a href="{{ route('center-panel.accommodations') }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold text-[#64748B] hover:text-[#1E2522] border border-[#2F6F57]/15 bg-white/80 hover:bg-white transition-all bb-sans">
                Cancel Changes
            </a>
            <button type="button" onclick="submitForm()"
                    class="px-5 py-2.5 rounded-xl bg-[#2F6F57] text-white font-semibold text-xs hover:bg-[#255a46] transition-all shadow-sm shadow-[#2F6F57]/20 bb-sans">
                Save Configuration
            </button>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 text-sm text-rose-700 space-y-1 bb-sans">
            @foreach ($errors->all() as $error)
                <p class="flex items-center gap-2 text-xs"><i class="fa-solid fa-exclamation-circle text-xs shrink-0"></i>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form id="frmAccommodation"
          action="{{ $accommodation ? route('center-panel.accommodation.update', $accommodation->id) : route('center-panel.accommodation.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- ── Left Column: Wizard ──────────────────────────────── --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Wizard Progress Tabs --}}
                <div class="grid grid-cols-3 bg-[#2F6F57]/6 p-1.5 rounded-2xl border border-[#2F6F57]/10 bb-sans">
                    <button type="button" onclick="switchStep(1)" id="tab-1" class="wizard-tab active">① Core Profile</button>
                    <button type="button" onclick="switchStep(2)" id="tab-2" class="wizard-tab">② Media Assets</button>
                    <button type="button" onclick="switchStep(3)" id="tab-3" class="wizard-tab">③ Review & Publish</button>
                </div>

                {{-- ─── Step 1: Core Profile ──────────────────────── --}}
                <fieldset id="step-1" class="space-y-5">
                    <div class="glass-premium rounded-2xl p-6 space-y-5">
                        <legend class="bb-serif text-lg font-medium text-[#1E2522]">Step 1: General Core Profiling</legend>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase text-[#1E2522] block tracking-wide bb-sans">
                                Accommodation Name <span class="text-[#2F6F57]">*</span>
                            </label>
                            <input type="text" id="f-name" name="name"
                                   value="{{ old('name', $accommodation->name ?? '') }}"
                                   oninput="syncPreview()"
                                   placeholder="e.g. Deluxe Private Garden Suite"
                                   class="premium-input bb-sans" required>
                            <p class="text-[11px] text-[#64748B] font-light">This title renders across public booking screens.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase text-[#1E2522] block tracking-wide bb-sans">
                                URL Slug <span class="text-[#2F6F57]">*</span>
                            </label>
                            <input type="text" id="f-slug" name="slug"
                                   value="{{ old('slug', $accommodation->slug ?? '') }}"
                                   placeholder="deluxe-private-garden-suite"
                                   class="premium-input font-mono bb-sans" required>
                            <p class="text-[11px] text-[#64748B] font-light">Auto-generated from name. Lowercase, hyphens only.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold uppercase text-[#1E2522] block tracking-wide bb-sans">Max Allocation Guest Capacity</label>
                            <select id="f-capacity" name="max_guest_in_room"
                                    onchange="syncPreview()"
                                    class="premium-input bb-sans">
                                <option value="">Select capacity</option>
                                @for ($i = 1; $i <= 20; $i++)
                                    <option value="{{ $i }}" {{ old('max_guest_in_room', $accommodation->max_guest_in_room ?? '') == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i === 1 ? 'guest' : 'guests' }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    {{-- Description with TinyMCE --}}
                    <div class="glass-premium rounded-2xl p-6 space-y-3">
                        <div>
                            <legend class="bb-serif text-lg font-medium text-[#1E2522]">Rich Text Description</legend>
                            <p class="text-[11px] text-[#64748B] font-light mt-0.5">Describe layout, views, amenities, and what makes it special.</p>
                        </div>
                        <div class="border border-[#2F6F57]/12 rounded-2xl overflow-hidden">
                            <textarea id="description" name="description" class="tiny-editor w-full">{{ old('description', $accommodation->description ?? '') }}</textarea>
                        </div>
                    </div>
                </fieldset>

                {{-- ─── Step 2: Media Assets ──────────────────────── --}}
                <fieldset id="step-2" class="space-y-5 hidden">
                    <div class="glass-premium rounded-2xl p-6 space-y-5">
                        <div>
                            <legend class="bb-serif text-lg font-medium text-[#1E2522]">Step 2: Media Assets Matrix</legend>
                            <p class="text-[11px] text-[#64748B] font-light mt-0.5">Upload the primary cover image and supplementary gallery frames.</p>
                        </div>

                        {{-- Banner Image --}}
                        <div class="space-y-3">
                            <label class="text-xs font-bold uppercase text-[#1E2522] block tracking-wide bb-sans">Primary Cover Backdrop Image</label>

                            @if($accommodation && $accommodation->banner_image_url)
                                <div id="banner_img_container" class="relative rounded-2xl overflow-hidden border border-[#2F6F57]/15">
                                    <img id="banner_current"
                                         src="{{ Storage::disk('s3')->url($accommodation->banner_image_url) }}"
                                         alt="{{ $accommodation->banner_image_title }}"
                                         class="w-full h-52 object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#1E2522]/50 via-transparent to-transparent"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-4 flex items-end justify-between">
                                        <span class="text-white/80 text-xs font-light truncate max-w-xs">{{ $accommodation->banner_image_title }}</span>
                                        <button type="button" id="btn_delete_banner"
                                                data-id="{{ $accommodation->id }}"
                                                data-url="{{ route('center-panel.accommodation.delete_banner_image') }}"
                                                class="bg-rose-500/90 hover:bg-rose-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg transition-all shrink-0 ml-3">
                                            ✕ Remove
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div id="banner_preview_wrap" class="hidden rounded-2xl overflow-hidden border border-[#2F6F57]/15 mb-2">
                                    <img id="banner_preview" src="" alt="Preview" class="w-full h-52 object-cover">
                                </div>
                            @endif

                            <label class="block w-full cursor-pointer group">
                                <div class="flex flex-col items-center justify-center gap-2.5 border-2 border-dashed border-[#2F6F57]/20 rounded-2xl p-8 group-hover:border-[#2F6F57]/50 group-hover:bg-[#2F6F57]/4 transition-all text-center">
                                    <span class="text-3xl">📸</span>
                                    <div class="text-xs text-[#1E2522] bb-sans">
                                        <span class="text-[#2F6F57] font-bold underline">Click to select media file</span> or drop luxury master asset
                                    </div>
                                    <p class="text-[10px] text-[#64748B] font-light">ProRes PNG, Ultra-HD JPEG, WebP — up to 10MB</p>
                                </div>
                                <input type="file" name="banner_image" id="banner_image" accept="image/*" class="sr-only">
                            </label>
                        </div>

                        {{-- Gallery --}}
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-bold uppercase text-[#1E2522] block tracking-wide bb-sans">Supplementary Asset Gallery Grid</label>
                                <p class="text-[11px] text-[#64748B] font-light mt-0.5">Upload multiple angles — drop files or click to browse.</p>
                            </div>

                            <div id="image_gallery" class="dropzone-premium bb-sans">
                                <div class="dz-message">
                                    <span class="text-2xl block mb-1">🖼️</span>
                                    <span class="font-medium text-[#64748B]">Drop gallery images here</span><br>
                                    <span class="text-[11px]">PNG, JPG, WEBP accepted</span>
                                </div>
                            </div>
                            <input type="hidden" id="image_gallery_ids" name="image_gallery_ids" value="">
                            <input type="hidden" id="dropzone_url" value="{{ route('center-panel.accommodation.upload_gallery_image') }}">

                            {{-- Existing gallery images --}}
                            @if(!empty($imagegalleries) && count($imagegalleries) > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3" id="existing_gallery">
                                @foreach($imagegalleries as $gallery)
                                <div id="gallery-img-{{ $gallery->id }}" class="relative group rounded-2xl overflow-hidden border border-[#2F6F57]/10">
                                    <img src="{{ Storage::disk('s3')->url($gallery->image_url) }}"
                                         alt="{{ $gallery->image_title }}"
                                         class="w-full h-24 object-cover">
                                    <div class="absolute inset-0 bg-[#1E2522]/45 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                        <button type="button"
                                                class="btn-delete-gallery text-white text-[10px] bg-rose-500 hover:bg-rose-600 px-3 py-1.5 rounded-xl font-bold transition-all"
                                                data-id="{{ $gallery->id }}"
                                                data-url="{{ route('center-panel.accommodation.delete_gallery_image') }}">
                                            ✕ Remove
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </fieldset>

                {{-- ─── Step 3: Review & Publish ──────────────────── --}}
                <fieldset id="step-3" class="space-y-5 hidden">
                    <div class="glass-premium rounded-2xl p-6 space-y-5">
                        <div>
                            <legend class="bb-serif text-lg font-medium text-[#1E2522]">Step 3: Review & Publish Configuration</legend>
                            <p class="text-[11px] text-[#64748B] font-light mt-0.5">Confirm your accommodation details before saving to the system.</p>
                        </div>

                        {{-- Review Summary --}}
                        <div class="bg-[#F8FAF8] rounded-2xl border border-[#2F6F57]/10 divide-y divide-[#2F6F57]/8">
                            <div class="flex items-center justify-between px-4 py-3 bb-sans">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#64748B]">Name</span>
                                <span id="review-name" class="text-xs font-semibold text-[#1E2522] text-right max-w-[60%] truncate">—</span>
                            </div>
                            <div class="flex items-center justify-between px-4 py-3 bb-sans">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#64748B]">Slug</span>
                                <span id="review-slug" class="text-[11px] font-mono text-[#64748B] text-right">—</span>
                            </div>
                            <div class="flex items-center justify-between px-4 py-3 bb-sans">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#64748B]">Max Guests</span>
                                <span id="review-capacity" class="text-xs font-semibold text-[#1E2522]">—</span>
                            </div>
                            <div class="flex items-center justify-between px-4 py-3 bb-sans">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#64748B]">Description</span>
                                <span id="review-desc" class="text-xs text-[#64748B] text-right max-w-[60%] line-clamp-1">—</span>
                            </div>
                        </div>

                        {{-- Completion Meter --}}
                        <div class="bg-[#F8FAF8] p-4 rounded-2xl border border-[#2F6F57]/10 space-y-2 bb-sans">
                            <div class="flex justify-between text-[10px] font-bold text-[#64748B] uppercase tracking-wider">
                                <span>Setup Completion Matrix</span>
                                <span id="completion-pct" class="text-[#2F6F57] font-mono">0%</span>
                            </div>
                            <div class="w-full h-2 bg-[#2F6F57]/10 rounded-full overflow-hidden">
                                <div id="completion-bar" class="h-full bg-gradient-to-r from-[#8BA888] to-[#2F6F57] rounded-full transition-all duration-500" style="width:0%"></div>
                            </div>
                        </div>

                        {{-- Danger Zone (edit only) --}}
                        @if($accommodation)
                        <div class="border-t border-rose-100 pt-5 space-y-3" x-data="{ confirmDelete: false }">
                            <h4 class="text-xs font-bold text-rose-600 uppercase tracking-wider flex items-center gap-2 bb-sans">
                                <i class="fa-solid fa-triangle-exclamation"></i> Danger Zone
                            </h4>
                            <button type="button"
                                    @click="confirmDelete = true"
                                    class="w-full py-2.5 rounded-xl border border-rose-200 text-rose-600 text-xs font-semibold hover:bg-rose-50 transition-all bb-sans">
                                Delete This Accommodation
                            </button>

                            <div x-show="confirmDelete" x-cloak
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
                                 @keydown.escape.window="confirmDelete = false">
                                <div class="bg-white rounded-3xl p-6 shadow-2xl max-w-sm w-full mx-4 border border-rose-100 bb-sans" @click.stop>
                                    <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4 text-xl">⚠️</div>
                                    <h3 class="bb-serif text-xl font-medium text-[#1E2522] text-center mb-1">Delete Accommodation?</h3>
                                    <p class="text-xs text-[#64748B] text-center mb-5 font-light">
                                        This permanently removes <strong class="text-[#1E2522]">{{ $accommodation->name }}</strong> from your center.
                                    </p>
                                    <div class="flex gap-3">
                                        <button @click="confirmDelete = false"
                                                class="flex-1 py-2.5 rounded-xl border border-[#2F6F57]/20 text-xs font-semibold text-[#64748B] hover:bg-slate-50 transition-all">
                                            Cancel
                                        </button>
                                        <form action="{{ route('center-panel.accommodation.destroy') }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $accommodation->id }}">
                                            <button type="submit" class="w-full py-2.5 rounded-xl bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700 transition-all">
                                                Delete Permanently
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </fieldset>

                {{-- Wizard Navigation --}}
                <div class="flex items-center justify-between pt-1 bb-sans">
                    <button type="button" id="btn-prev" onclick="navigateStep(-1)"
                            class="px-4 py-2.5 rounded-xl border border-[#2F6F57]/15 bg-white text-xs font-semibold text-[#64748B] hover:text-[#1E2522] transition-all disabled:opacity-30 cursor-pointer"
                            disabled>
                        ← Backtrack
                    </button>
                    <button type="button" id="btn-next" onclick="navigateStep(1)"
                            class="px-5 py-2.5 rounded-xl bg-[#2F6F57] text-white text-xs font-semibold hover:bg-[#255a46] transition-all shadow-sm shadow-[#2F6F57]/20 cursor-pointer">
                        Proceed Further →
                    </button>
                </div>
            </div>

            {{-- ── Right Column: Live Preview ──────────────────────── --}}
            <div class="lg:col-span-1 lg:sticky lg:top-6 space-y-5">

                {{-- Real-time Preview Card --}}
                <div>
                    <span class="text-[10px] font-bold tracking-widest text-[#2F6F57] uppercase block bb-sans">Real-Time Core Mockup</span>
                    <p class="text-[11px] text-[#64748B] font-light">Live snapshot emulating public viewport conversions.</p>
                </div>

                <div id="live-preview-card" class="bg-white rounded-3xl overflow-hidden border border-[#2F6F57]/12 shadow-xl">
                    {{-- Preview Image --}}
                    <div class="h-48 relative overflow-hidden preview-image-placeholder">
                        <img id="preview-img" src="{{ ($accommodation && $accommodation->banner_image_url) ? Storage::disk('s3')->url($accommodation->banner_image_url) : '' }}"
                             alt=""
                             class="{{ ($accommodation && $accommodation->banner_image_url) ? '' : 'hidden' }} w-full h-full object-cover transition-transform duration-700">
                        <div id="preview-img-placeholder"
                             class="{{ ($accommodation && $accommodation->banner_image_url) ? 'hidden' : '' }} w-full h-full flex flex-col items-center justify-center gap-2 text-[#2F6F57]/30">
                            <i class="fa-regular fa-image text-5xl"></i>
                            <span class="text-xs font-light bb-sans">No image yet</span>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1E2522]/30 to-transparent"></div>

                        {{-- Capacity badge --}}
                        <span id="preview-capacity-badge"
                              class="absolute top-3 right-3 bg-white/90 text-[#1E2522] text-[10px] font-bold px-2.5 py-1 rounded-full premium-badge {{ ($accommodation && $accommodation->max_guest_in_room) ? '' : 'hidden' }}">
                            👥 {{ $accommodation->max_guest_in_room ?? '' }} Guests
                        </span>
                    </div>

                    {{-- Preview Body --}}
                    <div class="p-5 space-y-3 bb-sans">
                        <div>
                            <h3 id="preview-name" class="bb-serif text-xl font-medium text-[#1E2522] tracking-tight line-clamp-1">
                                {{ $accommodation->name ?? 'Accommodation Name' }}
                            </h3>
                            <p id="preview-desc" class="text-[11px] text-[#64748B] line-clamp-2 mt-1 font-light leading-relaxed">
                                Describe layout properties, natural illumination characteristics...
                            </p>
                        </div>

                        {{-- Completion gauge --}}
                        <div class="bg-[#F8FAF8] p-3 rounded-xl border border-[#2F6F57]/8 space-y-1.5">
                            <div class="flex justify-between text-[10px] font-bold text-[#64748B] uppercase tracking-wider">
                                <span>Setup Completion</span>
                                <span id="preview-pct" class="text-[#2F6F57] font-mono">0%</span>
                            </div>
                            <div class="w-full h-1.5 bg-[#2F6F57]/10 rounded-full overflow-hidden">
                                <div id="preview-bar" class="h-full bg-gradient-to-r from-[#8BA888] to-[#2F6F57] rounded-full transition-all duration-500"
                                     style="width:{{ ($accommodation && $accommodation->name) ? '60%' : '0%' }}"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Save --}}
                <div class="glass-premium rounded-2xl p-4 space-y-3 bb-sans">
                    <button type="button" onclick="submitForm()"
                            class="w-full py-3 rounded-xl bg-[#2F6F57] text-white text-xs font-semibold hover:bg-[#255a46] transition-all shadow-sm shadow-[#2F6F57]/20 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-{{ $accommodation ? 'floppy-disk' : 'plus' }}"></i>
                        {{ $accommodation ? 'Save Changes' : 'Create Accommodation' }}
                    </button>
                    <a href="{{ route('center-panel.accommodations') }}"
                       class="w-full py-2.5 rounded-xl border border-[#2F6F57]/15 text-[#64748B] text-xs font-semibold hover:bg-slate-50 transition-all flex items-center justify-center gap-2 text-center block">
                        ← Back to Inventory
                    </a>
                </div>

                {{-- Edit Info --}}
                @if($accommodation)
                <div class="glass-premium rounded-2xl p-4 space-y-2 bb-sans">
                    <h4 class="text-xs font-bold uppercase text-[#1E2522] tracking-wide">System Record</h4>
                    <div class="text-[11px] space-y-1.5 text-[#64748B]">
                        <div class="flex items-start gap-2">
                            <span class="text-[#2F6F57]">●</span>
                            <p><strong class="text-[#1E2522]">Accommodation ID:</strong> #{{ $accommodation->id }}</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="text-[#D4AF37]">●</span>
                            <p><strong class="text-[#1E2522]">Last Modified:</strong> {{ $accommodation->updated_at ? $accommodation->updated_at->diffForHumans() : '—' }}</p>
                        </div>
                    </div>
                </div>
                @endif

            </div>

        </div>
    </form>

@endsection

@section('scripts')
<script src="{{ asset('admin/plugins/tinymce/tinymce.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script>
(function () {
    'use strict';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    let currentStep = 1;
    const totalSteps = 3;

    /* ── TinyMCE ─────────────────────────────────────────────────────── */
    tinymce.init({
        selector: 'textarea.tiny-editor',
        theme:    'modern',
        height:   300,
        skin_url: '/admin/plugins/tinymce/skins/lightgray',
        plugins:  ['advlist autolink link lists charmap searchreplace wordcount code paste textcolor'],
        toolbar:  'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link | forecolor | code',
        content_style: 'body { font-family: Outfit, sans-serif; font-size: 13px; color: #1E2522; line-height: 1.7; }',
        menubar:  false,
        statusbar: true,
        branding:  false,
        setup: function (editor) {
            editor.on('change keyup', function () {
                editor.save();
                syncPreview();
            });
        }
    });

    /* ── Wizard Steps ────────────────────────────────────────────────── */
    window.switchStep = function (n) {
        currentStep = n;
        for (let i = 1; i <= totalSteps; i++) {
            document.getElementById('step-' + i)?.classList.toggle('hidden', i !== n);
            const tab = document.getElementById('tab-' + i);
            if (tab) tab.classList.toggle('active', i === n);
        }
        document.getElementById('btn-prev').disabled = (n === 1);
        const nextBtn = document.getElementById('btn-next');
        nextBtn.textContent = (n === totalSteps) ? 'Publish Configuration ✓' : 'Proceed Further →';

        if (n === 3) updateReview();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    window.navigateStep = function (dir) {
        const next = currentStep + dir;
        if (next >= 1 && next <= totalSteps) {
            switchStep(next);
        } else if (next > totalSteps) {
            submitForm();
        }
    };

    /* ── Form Submit ─────────────────────────────────────────────────── */
    window.submitForm = function () {
        if (typeof tinymce !== 'undefined') tinymce.triggerSave();
        document.getElementById('frmAccommodation').submit();
    };

    /* ── Name → Slug auto-generate ───────────────────────────────────── */
    const nameInput = document.getElementById('f-name');
    const slugInput = document.getElementById('f-slug');

    nameInput?.addEventListener('blur', function () {
        if (!slugInput.value) {
            slugInput.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-|-$/g, '');
        }
        syncPreview();
    });

    /* ── Live Preview Sync ───────────────────────────────────────────── */
    window.syncPreview = function () {
        const name     = nameInput?.value || 'Accommodation Name';
        const capacity = document.getElementById('f-capacity')?.value;
        const desc     = document.getElementById('description')?.value || '';

        document.getElementById('preview-name').textContent = name || 'Accommodation Name';

        const plainDesc = desc.replace(/<[^>]+>/g, '').trim();
        document.getElementById('preview-desc').textContent = plainDesc
            ? (plainDesc.length > 90 ? plainDesc.slice(0, 90) + '...' : plainDesc)
            : 'Describe layout properties, natural illumination characteristics...';

        const capBadge = document.getElementById('preview-capacity-badge');
        if (capacity && capacity > 0) {
            capBadge.textContent = '👥 ' + capacity + (capacity == 1 ? ' Guest' : ' Guests');
            capBadge.classList.remove('hidden');
        } else {
            capBadge.classList.add('hidden');
        }

        // Completion
        let done = 0;
        const total = 4;
        if (name && name !== 'Accommodation Name') done++;
        if (slugInput?.value) done++;
        if (capacity) done++;
        if (plainDesc.length > 10) done++;
        const pct = Math.round((done / total) * 100);
        document.getElementById('preview-pct').textContent   = pct + '%';
        document.getElementById('preview-bar').style.width   = pct + '%';
        document.getElementById('completion-pct').textContent = pct + '%';
        document.getElementById('completion-bar').style.width = pct + '%';
    };

    syncPreview();
    document.getElementById('f-capacity')?.addEventListener('change', syncPreview);

    /* ── Review Step Sync ────────────────────────────────────────────── */
    function updateReview() {
        const name     = nameInput?.value || '—';
        const slug     = slugInput?.value || '—';
        const capacity = document.getElementById('f-capacity')?.value;
        const desc     = (document.getElementById('description')?.value || '').replace(/<[^>]+>/g, '').trim();

        document.getElementById('review-name').textContent     = name;
        document.getElementById('review-slug').textContent     = slug;
        document.getElementById('review-capacity').textContent = capacity ? capacity + ' guests' : 'Not set';
        document.getElementById('review-desc').textContent     = desc || 'No description';
    }

    /* ── Banner Image Preview ────────────────────────────────────────── */
    document.getElementById('banner_image')?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const img        = document.getElementById('preview-img');
            const placeholder = document.getElementById('preview-img-placeholder');
            if (img) { img.src = e.target.result; img.classList.remove('hidden'); }
            if (placeholder) placeholder.classList.add('hidden');

            const wrap = document.getElementById('banner_preview_wrap');
            const prev = document.getElementById('banner_preview');
            if (wrap && prev) { prev.src = e.target.result; wrap.classList.remove('hidden'); }
        };
        reader.readAsDataURL(file);
    });

    /* ── Delete Banner ───────────────────────────────────────────────── */
    document.getElementById('btn_delete_banner')?.addEventListener('click', async function (e) {
        e.preventDefault();
        if (!confirm('Remove the banner image?')) return;
        this.disabled = true;
        try {
            const res = await fetch(this.dataset.url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ id: this.dataset.id }),
            });
            if ((await res.text()).trim() === '1') {
                document.getElementById('banner_img_container')?.remove();
                const img        = document.getElementById('preview-img');
                const placeholder = document.getElementById('preview-img-placeholder');
                if (img) { img.src = ''; img.classList.add('hidden'); }
                if (placeholder) placeholder.classList.remove('hidden');
            }
        } finally { this.disabled = false; }
    });

    /* ── Delete Gallery Image ────────────────────────────────────────── */
    document.querySelectorAll('.btn-delete-gallery').forEach(function (btn) {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            if (!confirm('Remove this image?')) return;
            this.disabled = true;
            try {
                const res = await fetch(this.dataset.url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ id: this.dataset.id }),
                });
                if ((await res.text()).trim() === '1') {
                    document.getElementById('gallery-img-' + this.dataset.id)?.remove();
                }
            } finally { this.disabled = false; }
        });
    });

    /* ── Dropzone ────────────────────────────────────────────────────── */
    Dropzone.autoDiscover = false;
    const dzEl = document.getElementById('image_gallery');
    if (dzEl) {
        new Dropzone(dzEl, {
            url:            document.getElementById('dropzone_url').value,
            addRemoveLinks: true,
            acceptedFiles:  'image/*',
            dictRemoveFile: '✕ Remove',
            headers:        { 'X-CSRF-TOKEN': csrfToken },
            init: function () {
                this.on('success', function (file, response) {
                    if (response && response.success) {
                        const el = document.getElementById('image_gallery_ids');
                        el.value = el.value ? el.value + '|@|@|' + response.filename : response.filename;
                    }
                });
                this.on('removedfile', function (file) {
                    if (!file.xhr) return;
                    try {
                        const r  = JSON.parse(file.xhr.responseText);
                        const el = document.getElementById('image_gallery_ids');
                        el.value = el.value.replace('|@|@|' + r.filename, '').replace(r.filename, '');
                    } catch (_) {}
                });
            },
        });
    }

})();
</script>
@endsection
