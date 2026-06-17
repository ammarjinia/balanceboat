@extends('layouts.center')

@section('title', 'Center Profile - BalanceBoat')

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
<style>
    .bb-serif { font-family: 'Playfair Display', serif; }
    .bb-sans  { font-family: 'Outfit', sans-serif; }

    .glass-panel {
        background: rgba(255,255,255,0.75);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid rgba(255,255,255,0.6);
        box-shadow: 0 8px 32px 0 rgba(47,111,87,0.07);
    }

    .fi {
        background: #fff;
        border: 1px solid rgba(47,111,87,0.18);
        border-radius: 10px;
        padding: 9px 13px;
        font-size: 12.5px;
        width: 100%;
        font-family: 'Outfit', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s;
        color: #1A2421;
    }
    .fi:focus {
        outline: none;
        border-color: #2F6F57;
        box-shadow: 0 0 0 3px rgba(47,111,87,0.1);
    }
    .fi::placeholder { color: #94a3b8; }

    .fl {
        display: block;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #4B5563;
        margin-bottom: 5px;
        font-family: 'Outfit', sans-serif;
    }

    .wizard-tab {
        flex: 1;
        min-width: 40px;
        padding: 7px 8px;
        text-align: center;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
        color: #9CA3AF;
        background: transparent;
        border: none;
        outline: none;
    }
    .wizard-tab:hover { color: #2F6F57; background: rgba(47,111,87,0.05); }
    .wizard-tab.active { background: #2F6F57; color: #fff; box-shadow: 0 2px 8px rgba(47,111,87,0.25); }

    /* TinyMCE overrides inside wizard */
    .mce-panel  { border-color: rgba(47,111,87,0.18) !important; }
    .mce-toolbar { border-color: rgba(47,111,87,0.1) !important; }

    [x-cloak] { display: none !important; }
</style>
@endsection

@section('content')
@php
    $completionChecks = [
        'name', 'email_address', 'contact_number', 'address_of_center', 'city', 'country',
        'about_center', 'what_sets_us_apart', 'our_philosophy', 'our_mission',
        'founders', 'video_url', 'meta_title', 'meta_description',
    ];
    $filled     = collect($completionChecks)->filter(fn($f) => !empty($center->$f))->count();
    $completion = round(($filled / count($completionChecks)) * 100);
    $totalSteps = 4;
@endphp

{{-- Ambient bg blobs --}}
<div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden">
    <div class="absolute -top-40 -left-20 w-[70vw] h-[70vw] rounded-full bg-gradient-to-tr from-[#8BA888]/8 to-[#2F6F57]/4 blur-[120px]"></div>
    <div class="absolute -bottom-32 -right-10 w-[50vw] h-[50vw] rounded-full bg-gradient-to-br from-[#D4AF37]/5 to-[#8BA888]/8 blur-[100px]"></div>
</div>

<div x-data="{
        step: 1,
        totalSteps: {{ $totalSteps }},
        centerName: '{{ addslashes($center->name ?? '') }}',
        centerCity: '{{ addslashes($center->city ?? '') }}',
        centerCountry: '{{ addslashes($center->country ?? '') }}',

        switchStep(n) {
            this.step = n;
            window.scrollTo({ top: 0, behavior: 'smooth' });
            if (n === 2) {
                this.$nextTick(() => initSettingsTinyMCE());
            }
        },
        nextStep() {
            if (this.step < this.totalSteps) {
                this.step++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
                if (this.step === 2) {
                    this.$nextTick(() => initSettingsTinyMCE());
                }
            }
        },
        prevStep() {
            if (this.step > 1) {
                this.step--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        submitForm() {
            if (typeof tinymce !== 'undefined') tinymce.triggerSave();
            document.getElementById('center-settings-form').submit();
        }
     }"
     class="bb-sans">

    {{-- ── Page header ── --}}
    <div class="border-b border-[#2F6F57]/10 pb-5 mb-6 space-y-1">
        <p class="text-[10px] font-bold uppercase tracking-widest text-[#D4AF37]">Center Configuration Matrix</p>
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <h1 class="bb-serif text-3xl font-medium text-[#1A2421]">Center Profile</h1>
            <div class="flex items-center gap-2 text-xs text-[#64748B]">
                Step <span x-text="step" class="font-bold text-[#2F6F57]"></span> of {{ $totalSteps }}
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="glass-panel rounded-2xl px-4 py-3 flex items-center gap-3 text-sm text-emerald-700 mb-5">
        <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 rounded-2xl px-4 py-3 text-sm text-rose-700 mb-5 space-y-1">
        @foreach($errors->all() as $e)
        <p class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation"></i> {{ $e }}</p>
        @endforeach
    </div>
    @endif

    {{-- ── Main grid ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- ── Wizard left column ── --}}
        <div class="lg:col-span-7 space-y-5">

            {{-- Step tabs --}}
            <div class="glass-panel rounded-2xl p-2.5 flex gap-1.5 overflow-x-auto">
                @php $stepLabels = ['Identity & Contact', 'Our Story', 'Discovery', 'SEO & Publishing']; @endphp
                @foreach($stepLabels as $i => $label)
                <button type="button"
                        @click="switchStep({{ $i + 1 }})"
                        :class="step === {{ $i + 1 }} ? 'active' : ''"
                        class="wizard-tab">
                    <span class="block text-[9px] font-bold uppercase tracking-wider opacity-60"
                          x-text="step === {{ $i + 1 }} ? 'STEP {{ $i + 1 }}' : '{{ $i + 1 }}'"></span>
                    <span class="hidden sm:block text-[10px] mt-0.5">{{ $label }}</span>
                </button>
                @endforeach
            </div>

            {{-- FORM: wraps all steps --}}
            <form id="center-settings-form"
                  method="POST"
                  action="{{ route('center-panel.settings.update') }}">
                @csrf

                {{-- ══════════════ STEP 1: Identity & Contact ══════════════ --}}
                <div x-show="step === 1"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="glass-panel rounded-3xl p-6 space-y-5">

                    <div class="border-b border-[#2F6F57]/10 pb-3">
                        <h3 class="bb-serif text-xl font-medium text-[#1A2421]">Identity & Contact</h3>
                        <p class="text-xs text-[#64748B] font-light mt-0.5">Core profile information visible to guests and partners.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="fl">Center Name <span class="text-rose-500 normal-case font-bold">*</span></label>
                            <input type="text" name="name" class="fi" required
                                   value="{{ old('name', $center->name) }}"
                                   placeholder="e.g. Amanpuri Wellness Sanctuary"
                                   @input="centerName = $event.target.value">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="fl">Email Address</label>
                                <input type="email" name="email_address" class="fi"
                                       value="{{ old('email_address', $center->email_address) }}"
                                       placeholder="reservations@center.com">
                            </div>
                            <div>
                                <label class="fl">Contact Number</label>
                                <input type="tel" name="contact_number" class="fi"
                                       value="{{ old('contact_number', $center->contact_number) }}"
                                       placeholder="+91 98765 43210">
                            </div>
                        </div>

                        <div>
                            <label class="fl">WhatsApp Number</label>
                            <input type="tel" name="whatsapp_number" class="fi"
                                   value="{{ old('whatsapp_number', $center->whatsapp_number) }}"
                                   placeholder="+91 98765 43210">
                        </div>

                        <div>
                            <label class="fl">Physical Address</label>
                            <textarea name="address_of_center" rows="2" class="fi"
                                      style="resize:vertical"
                                      placeholder="Street / Village / Area">{{ old('address_of_center', $center->address_of_center) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="fl">City</label>
                                <input type="text" name="city" class="fi"
                                       value="{{ old('city', $center->city) }}"
                                       placeholder="e.g. Goa"
                                       @input="centerCity = $event.target.value">
                            </div>
                            <div>
                                <label class="fl">Country</label>
                                <input type="text" name="country" class="fi"
                                       value="{{ old('country', $center->country) }}"
                                       placeholder="e.g. India"
                                       @input="centerCountry = $event.target.value">
                            </div>
                        </div>

                        <div>
                            <label class="fl">GPS Coordinates <span class="normal-case font-normal tracking-normal text-[#64748B]">(lat, lon)</span></label>
                            <input type="text" name="gps" class="fi"
                                   value="{{ old('gps', $center->gps) }}"
                                   placeholder="15.4909° N, 73.8278° E">
                        </div>
                    </div>
                </div>

                {{-- ══════════════ STEP 2: Our Story ══════════════ --}}
                <div x-show="step === 2" x-cloak
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="glass-panel rounded-3xl p-6 space-y-6">

                    <div class="border-b border-[#2F6F57]/10 pb-3">
                        <h3 class="bb-serif text-xl font-medium text-[#1A2421]">Our Story</h3>
                        <p class="text-xs text-[#64748B] font-light mt-0.5">Narrative content that defines your center's identity and philosophy.</p>
                    </div>

                    {{-- About the Center --}}
                    <div>
                        <label class="fl">About the Center</label>
                        <textarea name="about_center" id="about_center" class="settings-tiny"
                                  placeholder="Introduce guests to your sanctuary — its history, environment, and healing approach..."
                        >{{ old('about_center', $center->about_center) }}</textarea>
                    </div>

                    {{-- What Sets Us Apart --}}
                    <div>
                        <label class="fl">What Sets Us Apart</label>
                        <textarea name="what_sets_us_apart" id="what_sets_us_apart" class="settings-tiny"
                                  placeholder="What makes your approach uniquely powerful and different from others?"
                        >{{ old('what_sets_us_apart', $center->what_sets_us_apart) }}</textarea>
                    </div>

                    {{-- Philosophy + Mission side by side --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="fl">Our Philosophy</label>
                            <textarea name="our_philosophy" id="our_philosophy" class="settings-tiny"
                                      placeholder="Core beliefs and therapeutic worldview..."
                            >{{ old('our_philosophy', $center->our_philosophy) }}</textarea>
                        </div>
                        <div>
                            <label class="fl">Our Mission</label>
                            <textarea name="our_mission" id="our_mission" class="settings-tiny"
                                      placeholder="What you exist to do in the world..."
                            >{{ old('our_mission', $center->our_mission) }}</textarea>
                        </div>
                    </div>

                    {{-- Center Highlights — add/remove bullet builder --}}
                    <div>
                        <label class="fl">Center Highlights</label>
                        <div x-data="centerHighlightsBuilder()" x-init="init()" class="space-y-2">

                            {{-- Bullet rows --}}
                            <div class="space-y-2 border-l-4 border-[#2F6F57]/40 pl-4 ml-1 bg-[#2F6F57]/3 rounded-2xl p-3">
                                <template x-for="(entry, idx) in entries" :key="idx">
                                    <div class="flex items-center gap-2 bg-white border border-[#2F6F57]/12 rounded-xl px-3 py-2.5 shadow-sm hover:border-[#2F6F57]/30 transition-all group">
                                        <span class="text-[#2F6F57] font-bold text-sm shrink-0">•</span>
                                        <input type="text"
                                               x-model="entry.text"
                                               @input="syncData()"
                                               placeholder="Add a highlight point..."
                                               class="flex-1 text-xs font-medium text-[#1A2421] bg-transparent border-none outline-none placeholder-slate-300 font-['Outfit']">
                                        <button type="button"
                                                @click="removeRow(idx)"
                                                class="shrink-0 text-slate-300 hover:text-rose-500 transition-colors opacity-0 group-hover:opacity-100">
                                            <i class="fa-solid fa-xmark text-[10px]"></i>
                                        </button>
                                    </div>
                                </template>

                                {{-- Empty state --}}
                                <p x-show="entries.length === 0"
                                   class="text-[11px] text-[#64748B] font-light py-2 text-center italic">
                                    No highlights yet. Click "Add Highlight" to start.
                                </p>
                            </div>

                            <button type="button"
                                    @click="addRow()"
                                    class="flex items-center gap-1.5 text-xs font-bold text-[#2F6F57] hover:text-[#255a46] transition-colors pt-1">
                                <i class="fa-solid fa-plus-circle text-sm"></i>
                                Add Highlight
                            </button>

                            <p class="text-[10px] text-[#64748B] font-light">Each line becomes a bullet point on your center's profile page.</p>

                            {{-- Hidden field synced to Alpine --}}
                            <textarea name="center_highlights" id="centerHighlightsField"
                                      class="hidden">{{ old('center_highlights', $center->center_highlights) }}</textarea>
                        </div>
                    </div>

                </div>

                {{-- ══════════════ STEP 3: Discovery ══════════════ --}}
                <div x-show="step === 3" x-cloak
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="glass-panel rounded-3xl p-6 space-y-5">

                    <div class="border-b border-[#2F6F57]/10 pb-3">
                        <h3 class="bb-serif text-xl font-medium text-[#1A2421]">Discovery & Media</h3>
                        <p class="text-xs text-[#64748B] font-light mt-0.5">Help guests discover and understand your center's heritage.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="fl">Founders</label>
                                <input type="text" name="founders" class="fi"
                                       value="{{ old('founders', $center->founders) }}"
                                       placeholder="Dr. Ravi Shankar, Maya Krishnan">
                            </div>
                            <div>
                                <label class="fl">Year of Foundation</label>
                                <input type="number" name="year_of_foundation" class="fi"
                                       value="{{ old('year_of_foundation', $center->year_of_foundation) }}"
                                       min="1800" max="{{ date('Y') }}" placeholder="e.g. 2009">
                            </div>
                        </div>

                        <div>
                            <label class="fl">Awards & Recognitions</label>
                            <textarea name="awards" rows="2" class="fi" style="resize:vertical"
                                      placeholder="Luxury Spa Awards 2024, AYUSH Gold Certified, Condé Nast Traveller Top 10">{{ old('awards', $center->awards) }}</textarea>
                        </div>

                        <div>
                            <label class="fl">Tags <span class="normal-case font-normal tracking-normal text-[#64748B]">(comma-separated)</span></label>
                            <input type="text" name="tags" class="fi"
                                   value="{{ old('tags', $center->tags) }}"
                                   placeholder="ayurveda, panchakarma, yoga, detox, meditation">
                        </div>

                        <div>
                            <label class="fl">Promo / Cinematic Video URL</label>
                            <div class="relative">
                                <i class="fa-brands fa-youtube absolute left-3 top-1/2 -translate-y-1/2 text-rose-400 text-sm pointer-events-none"></i>
                                <input type="url" name="video_url" class="fi" style="padding-left: 2.25rem"
                                       value="{{ old('video_url', $center->video_url) }}"
                                       placeholder="https://youtube.com/watch?v=...">
                            </div>
                        </div>

                        <div>
                            <label class="fl">BalanceGurus Profile Link</label>
                            <input type="url" name="balancegurus_profile_link" class="fi"
                                   value="{{ old('balancegurus_profile_link', $center->balancegurus_profile_link) }}"
                                   placeholder="https://balancegurus.com/centers/your-center">
                        </div>
                    </div>
                </div>

                {{-- ══════════════ STEP 4: SEO & Publishing ══════════════ --}}
                <div x-show="step === 4" x-cloak
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="glass-panel rounded-3xl p-6 space-y-5">

                    <div class="border-b border-[#2F6F57]/10 pb-3">
                        <h3 class="bb-serif text-xl font-medium text-[#1A2421]">SEO & Publishing</h3>
                        <p class="text-xs text-[#64748B] font-light mt-0.5">Control how search engines discover and display your center.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="fl">SEO Page Title</label>
                            <input type="text" name="meta_title" class="fi"
                                   value="{{ old('meta_title', $center->meta_title) }}"
                                   maxlength="255"
                                   placeholder="Amanpuri Sanctuary | Bespoke Luxury Ayurveda Retreat">
                            <p class="text-[11px] text-[#64748B] mt-1 font-light">Optimal: 50–60 characters.</p>
                        </div>

                        <div>
                            <label class="fl">Keywords</label>
                            <input type="text" name="keywords" class="fi"
                                   value="{{ old('keywords', $center->keywords) }}"
                                   placeholder="ayurveda retreat india, panchakarma goa, yoga wellness center">
                        </div>

                        <div>
                            <label class="fl">Meta Description</label>
                            <textarea name="meta_description" rows="3" class="fi" maxlength="500"
                                      style="resize:vertical"
                                      placeholder="A compelling 150–160 character summary of your center that appears in search results...">{{ old('meta_description', $center->meta_description) }}</textarea>
                            <p class="text-[11px] text-[#64748B] mt-1 font-light">Optimal: 150–160 characters.</p>
                        </div>

                        {{-- Read-only system info --}}
                        <div class="bg-[#2F6F57]/4 rounded-2xl border border-[#2F6F57]/10 p-4 space-y-3">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-[#2F6F57]/70">System Record</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                                <div>
                                    <span class="text-[#64748B] block text-[10px] uppercase tracking-wide">Center ID</span>
                                    <span class="font-mono font-semibold text-[#1A2421]">#{{ $center->id }}</span>
                                </div>
                                <div>
                                    <span class="text-[#64748B] block text-[10px] uppercase tracking-wide">URL Slug</span>
                                    <span class="font-mono text-[#2F6F57]">{{ $center->slug ?? 'Not set' }}</span>
                                </div>
                                <div>
                                    <span class="text-[#64748B] block text-[10px] uppercase tracking-wide">Type</span>
                                    <span class="text-[#1A2421]">{{ $center->center_type ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-[#64748B] block text-[10px] uppercase tracking-wide">Status</span>
                                    <span class="{{ $center->is_draft ? 'text-amber-600' : 'text-emerald-600' }} font-semibold">
                                        {{ $center->is_draft ? 'Draft' : 'Published' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-[#64748B] block text-[10px] uppercase tracking-wide">Last Updated</span>
                                    <span class="text-[#1A2421]">{{ $center->updated_at ? $center->updated_at->diffForHumans() : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Stepper nav ── --}}
                <div class="flex items-center justify-between pt-2">
                    <button type="button"
                            @click="prevStep()"
                            :disabled="step === 1"
                            :class="step === 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-white/80'"
                            class="glass-panel px-5 py-2.5 rounded-2xl text-sm font-semibold text-[#64748B] transition-all">
                        <i class="fa-solid fa-arrow-left text-xs mr-1.5"></i> Back
                    </button>

                    {{-- Dot indicators --}}
                    <div class="flex items-center gap-2">
                        <template x-for="i in totalSteps">
                            <button type="button"
                                    @click="switchStep(i)"
                                    :class="step === i
                                        ? 'w-6 h-2 bg-[#2F6F57] rounded-full'
                                        : 'w-2 h-2 bg-[#2F6F57]/20 rounded-full hover:bg-[#2F6F57]/40'"
                                    class="transition-all duration-300"></button>
                        </template>
                    </div>

                    <template x-if="step < totalSteps">
                        <button type="button"
                                @click="nextStep()"
                                class="px-5 py-2.5 bg-[#2F6F57] text-white text-sm font-semibold rounded-2xl hover:bg-[#255a46] active:scale-95 transition-all shadow-sm">
                            Next <i class="fa-solid fa-arrow-right text-xs ml-1.5"></i>
                        </button>
                    </template>
                    <template x-if="step === totalSteps">
                        <button type="button"
                                @click="submitForm()"
                                class="px-5 py-2.5 bg-[#2F6F57] text-white text-sm font-semibold rounded-2xl hover:bg-[#255a46] active:scale-95 transition-all shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk text-xs"></i> Save All Changes
                        </button>
                    </template>
                </div>

            </form>
        </div>

        {{-- ── Right sticky sidebar ── --}}
        <aside class="lg:col-span-5 sticky top-6 space-y-4">

            <div class="text-[10px] font-bold uppercase tracking-widest text-[#64748B] pl-1">Live Profile Preview</div>

            <div class="glass-panel rounded-3xl overflow-hidden">
                {{-- Banner --}}
                <div class="h-40 relative bg-gradient-to-br from-[#2F6F57] to-[#1A4035] flex items-end">
                    @if($center->banner_image_url)
                    <img src="{{ Storage::disk('azure')->url($center->banner_image_url) }}"
                         alt="{{ $center->name }}"
                         class="absolute inset-0 w-full h-full object-cover opacity-60">
                    @endif
                    <div class="relative z-10 p-5 text-white w-full">
                        <div class="flex items-end justify-between">
                            <div class="min-w-0">
                                <p class="text-[10px] text-[#8BA888] uppercase tracking-widest font-semibold truncate">
                                    <span x-text="(centerCity || '{{ addslashes($center->city ?? '') }}') + ((centerCountry || '{{ addslashes($center->country ?? '') }}') ? ', ' + (centerCountry || '{{ addslashes($center->country ?? '') }}') : '')"></span>
                                </p>
                                <h4 class="bb-serif text-lg font-medium leading-tight mt-0.5 truncate">
                                    <span x-text="centerName || '{{ addslashes($center->name ?? 'Your Center') }}'"></span>
                                </h4>
                            </div>
                            <div class="text-right shrink-0 ml-3">
                                <p class="text-[10px] text-white/60">Completion</p>
                                <p class="bb-serif text-xl font-semibold text-[#D4AF37]">{{ $completion }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Completion bar --}}
                <div class="px-5 pt-4 pb-2">
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-[#64748B] font-medium">Profile Completion</span>
                        <span class="font-bold text-[#2F6F57]">{{ $completion }}%</span>
                    </div>
                    <div class="w-full bg-[#2F6F57]/10 rounded-full h-1.5">
                        <div class="bg-[#2F6F57] h-1.5 rounded-full" style="width: {{ $completion }}%"></div>
                    </div>
                </div>

                {{-- Quick stats --}}
                <div class="grid grid-cols-2 gap-3 px-5 py-4">
                    <div class="bg-[#F8FAF8] rounded-2xl p-3 border border-[#2F6F57]/8">
                        <span class="text-[9px] text-[#64748B] uppercase tracking-wider font-semibold block">Status</span>
                        <span class="text-sm font-bold {{ $center->is_draft ? 'text-amber-600' : 'text-[#2F6F57]' }} mt-0.5 block">
                            {{ $center->is_draft ? 'Draft' : 'Published' }}
                        </span>
                    </div>
                    <div class="bg-[#F8FAF8] rounded-2xl p-3 border border-[#2F6F57]/8">
                        <span class="text-[9px] text-[#64748B] uppercase tracking-wider font-semibold block">Category</span>
                        <span class="text-xs font-semibold text-[#1A2421] mt-0.5 block truncate">{{ $center->center_type ?: '—' }}</span>
                    </div>
                    <div class="bg-[#F8FAF8] rounded-2xl p-3 border border-[#2F6F57]/8">
                        <span class="text-[9px] text-[#64748B] uppercase tracking-wider font-semibold block">Location</span>
                        <span class="text-xs font-semibold text-[#1A2421] mt-0.5 block truncate">
                            {{ $center->city ?? '-' }}{{ $center->country ? ', '.$center->country : '' }}
                        </span>
                    </div>
                    <div class="bg-[#F8FAF8] rounded-2xl p-3 border border-[#2F6F57]/8">
                        <span class="text-[9px] text-[#64748B] uppercase tracking-wider font-semibold block">Founded</span>
                        <span class="text-xs font-semibold text-[#1A2421] mt-0.5 block">{{ $center->year_of_foundation ?? '-' }}</span>
                    </div>
                </div>

                {{-- Save --}}
                <div class="px-5 pb-5 space-y-2">
                    <button type="button"
                            @click="submitForm()"
                            class="w-full py-3 bg-[#2F6F57] text-white rounded-2xl text-sm font-bold hover:bg-[#255a46] active:scale-95 transition-all flex items-center justify-center gap-2 shadow-sm shadow-[#2F6F57]/20">
                        <i class="fa-solid fa-floppy-disk"></i> Save Profile
                    </button>
                    <a href="{{ route('center-panel.dashboard') }}"
                       class="w-full py-2.5 border border-[#2F6F57]/20 text-[#64748B] rounded-2xl text-xs font-semibold hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Back to Dashboard
                    </a>
                </div>
            </div>

            {{-- Account security --}}
            <div class="glass-panel rounded-3xl p-5 space-y-3">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#64748B]">Account & Security</h3>
                <div class="space-y-2">
                    <div class="flex items-center justify-between p-3 bg-[#F8FAF8] rounded-2xl border border-[#2F6F57]/8">
                        <div>
                            <p class="text-xs font-semibold text-[#1A2421]">Change Password</p>
                            <p class="text-[11px] text-[#64748B] font-light mt-0.5">Update your login credentials</p>
                        </div>
                        <button type="button" class="text-[11px] font-semibold text-[#2F6F57] hover:underline shrink-0">Change</button>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-[#F8FAF8] rounded-2xl border border-[#2F6F57]/8">
                        <div>
                            <p class="text-xs font-semibold text-[#1A2421]">Two-Factor Auth</p>
                            <p class="text-[11px] text-[#64748B] font-light mt-0.5">Add an extra layer of security</p>
                        </div>
                        <button type="button" class="text-[11px] font-semibold text-[#2F6F57] hover:underline shrink-0">Enable</button>
                    </div>
                </div>
            </div>

            {{-- Tips --}}
            <div class="rounded-3xl border border-[#2F6F57]/15 bg-[#2F6F57]/4 p-4 space-y-2">
                <p class="text-xs font-bold text-[#2F6F57] flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info"></i> Profile Completion Tips
                </p>
                <ul class="text-[11px] text-[#2F6F57]/80 space-y-1.5 leading-relaxed">
                    <li class="flex items-start gap-2"><i class="fa-solid fa-check text-[#2F6F57]/50 text-[9px] mt-1 shrink-0"></i> Fill "About the Center" to boost discoverability</li>
                    <li class="flex items-start gap-2"><i class="fa-solid fa-check text-[#2F6F57]/50 text-[9px] mt-1 shrink-0"></i> GPS coordinates enable map-based search</li>
                    <li class="flex items-start gap-2"><i class="fa-solid fa-check text-[#2F6F57]/50 text-[9px] mt-1 shrink-0"></i> A strong meta description improves click rates</li>
                    <li class="flex items-start gap-2"><i class="fa-solid fa-check text-[#2F6F57]/50 text-[9px] mt-1 shrink-0"></i> Tags help match guests searching by retreat type</li>
                </ul>
            </div>

        </aside>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('admin/plugins/tinymce/tinymce.min.js') }}"></script>
<script>
/* ── TinyMCE: lazy-init when Step 2 first becomes visible ── */
let _tinyInitialized = false;

function initSettingsTinyMCE() {
    if (_tinyInitialized) return;
    if (typeof tinymce === 'undefined') return;

    _tinyInitialized = true;

    tinymce.init({
        selector: 'textarea.settings-tiny',
        theme:    'modern',
        height:   220,
        skin_url: '/admin/plugins/tinymce/skins/lightgray',
        plugins:  ['advlist autolink link lists charmap searchreplace wordcount paste textcolor'],
        toolbar:  'bold italic underline | alignleft aligncenter alignright | bullist numlist | link | forecolor',
        content_style: 'body { font-family: Outfit, sans-serif; font-size: 13px; color: #1A2421; line-height: 1.7; }',
        menubar:  false,
        statusbar: false,
        setup: function (editor) {
            editor.on('change keyup', function () { editor.save(); });
        }
    });
}

/* ── Alpine: Center Highlights bullet builder ── */
document.addEventListener('alpine:init', () => {
    Alpine.data('centerHighlightsBuilder', () => ({
        entries: [],

        init() {
            const field = document.getElementById('centerHighlightsField');
            const raw   = field ? field.value.trim() : '';
            if (raw) {
                this.entries = raw.split('\n')
                    .map(l => l.trim())
                    .filter(l => l)
                    .map(l => ({ text: l.replace(/^[•\-\*]\s*/, '') }));
            } else {
                this.entries = [];
            }
        },

        addRow() {
            this.entries.push({ text: '' });
            this.$nextTick(() => {
                const inputs = document.querySelectorAll('#centerHighlightsField ~ div input[type="text"]');
                if (inputs.length) inputs[inputs.length - 1].focus();
                this.syncData();
            });
        },

        removeRow(idx) {
            this.entries.splice(idx, 1);
            this.syncData();
        },

        syncData() {
            const field = document.getElementById('centerHighlightsField');
            if (field) {
                field.value = this.entries
                    .filter(e => e.text.trim())
                    .map(e => '• ' + e.text.trim())
                    .join('\n');
            }
        }
    }));
});
</script>
@endsection
