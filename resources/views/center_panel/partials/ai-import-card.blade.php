{{-- "Fill my form" — AI retreat import, create mode only.

     Complement to partials/ai-structure-modal, which only exists on the edit form because it works
     on an already-saved record. This one runs before any record exists: the center gives their
     website address and the retreat name, and the whole six-step wizard is filled in place.

     Deliberately NOT a modal and NOT a review screen. The wizard itself is the review — filled
     inputs get a badge that clears when touched, and anything the model was unsure about, plus
     anything it could not find, becomes an inline amber note pinned to that actual field. The
     result strip lists those fields as chips that jump to the step holding them.

     Nothing is saved here. The form is filled client-side and the center still presses Create, so
     CenterDashboardController@experienceStore does the validating and the writing exactly as it
     does for a hand-typed retreat. "Undo fill" restores the snapshot taken before the fill.

     Money and dates are never filled by design — see RetreatImportService. --}}

{{-- Endpoints are passed as arguments, matching how partials/ai-structure-modal takes its
     experience id. They were on data attributes read through $el, which silently produced
     undefined: Alpine resolves $el against the element whose expression fired, so a method called
     from the button's @click saw the button, not this root div. --}}
@php
    $importExtractUrl = route('center-panel.experience.import.extract');
    $importGenerateUrl = route('center-panel.experience.import.generate');
@endphp
<div x-data='retreatImportCard(@json($importExtractUrl), @json($importGenerateUrl))'
     x-init="init()"
     class="rounded-3xl border border-purple-200 bg-gradient-to-br from-purple-50 via-white to-fuchsia-50 p-5 shadow-sm">

    {{-- ── Idle: the two inputs ─────────────────────────────────────── --}}
    <template x-if="state === 'idle' || state === 'error'">
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <div class="h-9 w-9 shrink-0 rounded-2xl bg-gradient-to-br from-purple-600 to-fuchsia-600 flex items-center justify-center shadow-sm shadow-purple-200">
                    <i class="fa-solid fa-wand-magic-sparkles text-white text-xs"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm font-semibold text-slate-900">Already have this retreat on your website?</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Give us the web address and we'll fill in all six steps for you. You review it and press Create.
                        We never fill in prices or dates.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="md:col-span-2">
                    <label class="wiz-label">Your website address</label>
                    <input type="text" x-model="url" @keydown.enter.prevent="start()"
                           placeholder="yourcenter.com/retreats/ayurveda"
                           class="wiz-input bg-white">
                </div>
                <div class="md:col-span-2">
                    <label class="wiz-label">Retreat name</label>
                    <input type="text" x-model="retreatName" @keydown.enter.prevent="start()"
                           placeholder="7-Day Panchakarma Retreat"
                           class="wiz-input bg-white">
                </div>
                <div class="flex items-end">
                    <button type="button" @click="start()" :disabled="!canStart()"
                            class="w-full py-2.5 px-4 bg-gradient-to-r from-purple-600 to-fuchsia-600 text-white rounded-xl text-xs font-bold hover:from-purple-700 hover:to-fuchsia-700 active:scale-95 transition-all shadow-sm shadow-purple-200 disabled:opacity-40 disabled:cursor-not-allowed disabled:active:scale-100">
                        Fill my form
                    </button>
                </div>
            </div>

            <div>
                <button type="button" @click="showPaste = !showPaste"
                        class="text-[11px] font-semibold text-slate-500 hover:text-purple-600 transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-paste text-[10px]"></i>
                    <span x-text="showPaste ? 'Hide the paste box' : 'No website, or site not loading? Paste your description instead'"></span>
                </button>
                <div x-show="showPaste" x-cloak class="mt-2 space-y-2">
                    <textarea x-model="pastedText" rows="6"
                              placeholder="Paste your brochure, itinerary or retreat description here. Anything you have is fine."
                              class="wiz-input bg-white text-xs resize-y"></textarea>
                    <p class="text-[10px] text-slate-400">
                        Works on its own if you leave the web address blank, and improves the result if you fill both.
                    </p>
                </div>
            </div>

            <template x-if="state === 'error'">
                <div class="bg-red-50 border border-red-200 text-red-700 text-xs rounded-2xl px-4 py-3 space-y-2">
                    <p x-text="errorMessage"></p>
                    {{-- How far it got before failing. Tells a reader (and support) whether the
                         website read succeeded and the model call broke, or neither ran. --}}
                    <template x-if="progress.length">
                        <div class="text-[10px] text-red-500/80 border-t border-red-200 pt-2">
                            <template x-for="(line, idx) in progress" :key="idx">
                                <p x-text="line"></p>
                            </template>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </template>

    {{-- ── Working: named progress lines, not a spinner ──────────────── --}}
    <template x-if="state === 'working'">
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-notch fa-spin text-purple-500"></i>
                <p class="text-sm font-semibold text-slate-800">Filling your form…</p>
            </div>
            <div class="space-y-1.5 pl-7">
                <template x-for="(line, idx) in progress" :key="idx">
                    <p class="text-xs text-slate-500 flex items-center gap-2">
                        <i class="fa-solid fa-check text-[9px] text-emerald-500"
                           x-show="idx < progress.length - 1"></i>
                        <i class="fa-solid fa-circle text-[6px] text-purple-400"
                           x-show="idx === progress.length - 1"></i>
                        <span x-text="line"></span>
                    </p>
                </template>
            </div>
            <p class="text-[10px] text-slate-400 pl-7">This usually takes under a minute. Please keep this tab open.</p>
        </div>
    </template>

    {{-- ── Done: result strip + things to check ─────────────────────── --}}
    <template x-if="state === 'done'">
        <div class="space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-8 w-8 shrink-0 rounded-2xl bg-emerald-100 flex items-center justify-center">
                        <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900">
                            Filled <span x-text="filledCount"></span> fields<span x-show="sourceLabel"> from <span x-text="sourceLabel" class="font-mono text-xs"></span></span>.
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Go through the steps and change anything that isn't right, then press Create Retreat.
                        </p>
                    </div>
                </div>
                <button type="button" @click="undo()"
                        class="text-[11px] font-semibold text-slate-500 hover:text-red-600 transition-all flex items-center gap-1.5 shrink-0">
                    <i class="fa-solid fa-rotate-left text-[10px]"></i> Undo fill
                </button>
            </div>

            <template x-if="!foundRetreat">
                <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs rounded-2xl px-4 py-3">
                    We couldn't find that exact retreat on the site, so we filled what we could from your
                    center's pages. Check each step carefully, or paste the retreat description and try again.
                </div>
            </template>

            <template x-if="checkItems.length">
                <div class="bg-white/70 border border-amber-200 rounded-2xl px-4 py-3">
                    <p class="text-[11px] font-semibold text-amber-800 mb-2">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i>
                        <span x-text="checkItems.length"></span> field(s) need your eyes:
                    </p>
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="item in checkItems" :key="item.name">
                            <button type="button" @click="jumpTo(item.step)"
                                    class="px-2.5 py-1 rounded-full bg-amber-100 hover:bg-amber-200 text-amber-800 text-[10px] font-semibold transition-all">
                                <span x-text="item.label"></span>
                                <span class="opacity-60">· step <span x-text="item.step"></span></span>
                            </button>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </template>
</div>

<style>
    /* A filled input announces itself without shouting, and stops as soon as it is touched. */
    .ai-filled {
        border-color: #c084fc !important;
        background-image: linear-gradient(to right, rgba(192, 132, 252, 0.07), transparent 60%);
    }
    /* Chip and tile groups have no single input to outline, so the wrapper carries the mark. */
    .ai-filled-group {
        position: relative;
    }
    .ai-filled-group::before {
        content: '';
        position: absolute;
        left: -10px;
        top: 0;
        bottom: 0;
        width: 3px;
        border-radius: 3px;
        background: #c084fc;
    }
    .ai-note {
        margin-top: 6px;
        font-size: 10.5px;
        line-height: 1.5;
        color: #b45309;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 6px 10px;
    }
</style>

<script>
function retreatImportCard(extractUrl, generateUrl) {
    return {
        extractUrl: extractUrl,
        generateUrl: generateUrl,
        state: 'idle',
        url: '',
        retreatName: '',
        pastedText: '',
        showPaste: false,
        progress: [],
        errorMessage: '',
        filledCount: 0,
        sourceLabel: '',
        foundRetreat: true,
        checkItems: [],
        snapshot: null,

        init() {
            // If the center already typed a title in step 1, start from it.
            const nameField = document.getElementById('field_name');
            if (nameField && nameField.value.trim()) {
                this.retreatName = nameField.value.trim();
            }
        },

        canStart() {
            return this.retreatName.trim() !== ''
                && (this.url.trim() !== '' || this.pastedText.trim() !== '');
        },

        csrf() {
            return document.querySelector('meta[name="csrf-token"]')?.content || '';
        },

        /**
         * Our own failures come back as {"error": "..."} and are safe to show as-is. Framework
         * failures do not — Laravel sends {"message": "..."} for CSRF, throttling and server
         * errors, and an HTML page when the response isn't JSON at all. Collapsing all of those
         * into one "something went wrong" makes a production problem undiagnosable, so translate
         * the status into something the center can act on and keep the detail in the console.
         */
        async post(url, body) {
            // fetch(undefined) resolves "undefined" against the current path and 404s against a
            // route nobody wrote, which reads like a server problem. Fail loudly at the source.
            if (!url) {
                throw new Error('This feature is misconfigured (no endpoint). Please reload the page, and tell support if it persists.');
            }

            let res;
            try {
                res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': this.csrf(),
                    },
                    body: JSON.stringify(body),
                });
            } catch (e) {
                throw new Error('We could not reach the server. Check your connection and try again.');
            }

            const raw = await res.text();
            let data = {};
            try { data = JSON.parse(raw); } catch (e) { /* an HTML error page, handled below */ }

            if (res.ok) return data;

            console.error('Retreat import failed', { url, status: res.status, body: raw.slice(0, 2000) });

            if (data.error) throw new Error(data.error);

            switch (res.status) {
                case 419:
                    throw new Error('Your session expired. Please reload the page and try again.');
                case 429:
                    throw new Error('You have used all your imports for now. Please try again later.');
                case 403:
                    // Shared hosting firewalls (ModSecurity and friends) reject large POST bodies
                    // of scraped HTML as if they were an attack. Worth naming, because the fix is
                    // a server rule, not anything the center can do differently.
                    throw new Error('The server blocked this request. Your host may be filtering large uploads — please send this to support.');
                case 413:
                    throw new Error('That page was too large to send. Try a more specific page on your site.');
                case 504:
                case 524:
                    throw new Error('Your website took too long to read. Try the page for this one retreat, or paste your description instead.');
                default:
                    throw new Error(
                        (data.message ? data.message + ' ' : '')
                        + `(server error ${res.status}) — please send this code to support if it keeps happening.`
                    );
            }
        },

        async start() {
            if (!this.canStart()) return;

            this.state = 'working';
            this.progress = [];
            this.errorMessage = '';

            try {
                let pages = [];

                if (this.url.trim() !== '') {
                    this.progress.push('Opening your website…');
                    const extracted = await this.post(
                        this.extractUrl,
                        { url: this.url.trim(), retreat_name: this.retreatName.trim() }
                    );
                    pages = extracted.pages || [];
                    this.sourceLabel = this.hostOf(extracted.start_url || this.url);
                    this.progress.push(`Read ${extracted.page_count} page${extracted.page_count === 1 ? '' : 's'} from your site.`);
                    (extracted.notes || []).forEach(n => this.progress.push(n));
                } else {
                    this.sourceLabel = 'your pasted text';
                }

                this.progress.push('Writing your retreat details…');
                const result = await this.post(
                    this.generateUrl,
                    {
                        retreat_name: this.retreatName.trim(),
                        pages: pages,
                        pasted_text: this.pastedText.trim(),
                    }
                );

                this.apply(result);
                this.state = 'done';
                this.jumpTo(1);
            } catch (e) {
                this.errorMessage = e.message;
                this.state = 'error';
            }
        },

        hostOf(url) {
            try { return new URL(url).host; } catch (e) { return url; }
        },

        // ── filling the wizard ───────────────────────────────────────

        apply(result) {
            this.takeSnapshot();
            this.clearNotes();

            const fields = result.fields || {};

            // The title is what the center typed, not something the model guessed at.
            this.setValue('name', this.retreatName.trim());

            Object.entries(fields).forEach(([name, field]) => {
                const value = field.value;

                switch (name) {
                    case 'category_names':
                    case 'destination_names':
                        break; // both arrive resolved to IDs below
                    case 'language_spoken':
                        this.setCheckboxGroup('language_spoken[]', value, v => v);
                        break;
                    case 'skill_level':
                        this.setRadio('skill_level', value);
                        break;
                    case 'durations':
                        // The rows are rebuilt by Alpine, so marking an individual input here would
                        // be wiped by the re-render. The amber note on the container carries it.
                        window.dispatchEvent(new CustomEvent('retreat-import-durations', { detail: { durations: value } }));
                        break;
                    case 'experience_schedule':
                        this.setHidden('scheduleDataField', value);
                        window.dispatchEvent(new CustomEvent('retreat-import-schedule'));
                        break;
                    case 'experience_highlights':
                        this.setHidden('highlightsDataField', value);
                        window.dispatchEvent(new CustomEvent('retreat-import-highlights'));
                        break;
                    default:
                        this.setValue(name, value, field.type);
                }
            });

            if ((result.category_ids || []).length) {
                this.setCheckboxGroup('experience_category_id[]', result.category_ids, v => String(v));
            }

            this.filledCount = result.filled_count || 0;
            this.foundRetreat = result.found_retreat !== false;

            this.buildCheckList(fields, result.missing || []);
        },

        /** Everything we are about to overwrite, so "Undo fill" is a real restore. */
        takeSnapshot() {
            const form = document.getElementById('experienceForm');
            if (!form) return;
            if (typeof tinymce !== 'undefined') tinymce.triggerSave();
            this.snapshot = Array.from(form.querySelectorAll('[name]')).map(el => ({
                el,
                value: el.value,
                checked: el.checked,
            }));
        },

        undo() {
            if (this.snapshot) {
                this.snapshot.forEach(({ el, value, checked }) => {
                    el.value = value;
                    el.checked = checked;
                    this.syncEditor(el, value);
                    el.classList.remove('ai-filled');
                });
                window.dispatchEvent(new CustomEvent('retreat-import-schedule'));
                window.dispatchEvent(new CustomEvent('retreat-import-highlights'));
            }
            document.querySelectorAll('.ai-filled').forEach(el => el.classList.remove('ai-filled'));
            this.clearNotes();
            this.snapshot = null;
            this.checkItems = [];
            this.state = 'idle';
        },

        setValue(name, value, type) {
            const el = document.querySelector(`#experienceForm [name="${name}"]`);
            if (!el || value === '' || value === null || value === undefined) return;

            // A plain_lines field carries one point per line, but the public page recovers those
            // points by strip_tags()ing the stored value and splitting on newlines. Handing raw
            // newlines to TinyMCE collapses them into one paragraph and the points are lost, so
            // wrap each line in its own <p> with a real newline between them — that survives both
            // the editor and the strip_tags() on the way out.
            const forEditor = (type === 'plain_lines' && el.classList.contains('tiny-editor'))
                ? String(value).split('\n').filter(l => l.trim()).map(l => `<p>${l.trim()}</p>`).join('\n')
                : value;

            el.value = forEditor;
            this.syncEditor(el, forEditor);
            el.dispatchEvent(new Event('input', { bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));
            this.mark(el);
        },

        /** TinyMCE keeps its own copy of the textarea, so setting .value alone is invisible. */
        syncEditor(el, value) {
            if (typeof tinymce === 'undefined' || !window.tinymce.editors) return;
            const editor = window.tinymce.editors.find(ed => ed.getElement() === el);
            if (editor) editor.setContent(value || '');
        },

        setHidden(id, value) {
            const el = document.getElementById(id);
            if (!el || !value) return;
            el.value = value;
        },

        /**
         * Replaces the group rather than adding to it. Languages start with English ticked by
         * default, and leaving that on top of what we read would claim a language the center
         * never listed. "Undo fill" is what puts the default back.
         */
        setCheckboxGroup(name, values, normalize) {
            if (!Array.isArray(values) || !values.length) return;
            const wanted = values.map(normalize);
            const boxes = document.querySelectorAll(`#experienceForm [name="${name}"]`);
            boxes.forEach(box => {
                const shouldCheck = wanted.includes(box.value);
                if (box.checked !== shouldCheck) {
                    box.checked = shouldCheck;
                    box.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
            this.markGroup(name);
        },

        setRadio(name, value) {
            if (!value) return;
            const radio = document.querySelector(`#experienceForm [name="${name}"][value="${value}"]`);
            if (!radio) return;
            radio.checked = true;
            radio.dispatchEvent(new Event('change', { bubbles: true }));
            this.markGroup(name);
        },

        /** The badge clears the moment the center edits the field — it means "we typed this", not "locked". */
        mark(el) {
            if (!el || el.type === 'hidden') return;
            el.classList.add('ai-filled');
            const clear = () => el.classList.remove('ai-filled');
            el.addEventListener('input', clear, { once: true });
            el.addEventListener('change', clear, { once: true });
        },

        markGroup(name) {
            const first = document.querySelector(`#experienceForm [name="${name}"]`);
            const wrapper = first?.closest('div');
            if (!wrapper) return;
            wrapper.classList.add('ai-filled-group');
        },

        // ── what needs checking ──────────────────────────────────────

        /**
         * Review by exception. A field is surfaced when it is one we always want a human to confirm
         * (schedule, nights, capacity, coordinates, video), when the model said it was guessing, or
         * when it found nothing at all. Everything else is simply filled — it is visible in the
         * wizard on the next scroll, and a second approval gate for text the center is about to
         * read anyway is ceremony, not safety.
         */
        buildCheckList(fields, missing) {
            const items = [];

            Object.entries(fields).forEach(([name, field]) => {
                if (name === 'category_names' || name === 'destination_names') return;
                if (field.verify || field.confidence === 'low') {
                    items.push({ name, label: field.label, step: field.step });
                    this.attachNote(
                        name,
                        field.verify
                            ? `We read this from your website — please confirm it's right.`
                            : `We weren't certain about this one. Worth a quick look.`
                    );
                }
            });

            missing.forEach(m => {
                items.push({ name: m.name, label: m.label, step: m.step });
                this.attachNote(m.name, `We couldn't find this on your website. Add it yourself if it applies.`);
            });

            this.checkItems = items;
        },

        attachNote(name, text) {
            let el = document.querySelector(`#experienceForm [name="${name}"]`)
                || document.querySelector(`#experienceForm [name="${name}[]"]`);

            // Anchor to containers, not to inputs Alpine re-renders (which would drop the note).
            if (name === 'durations') el = document.getElementById('duration-packages');
            if (name === 'experience_schedule') el = document.getElementById('schedule-rows-container');
            if (name === 'experience_highlights') el = document.getElementById('highlights-rows-container');
            if (name === 'category_names' || name === 'destination_names') {
                el = document.querySelector('#experienceForm [name="experience_category_id[]"]');
            }
            if (!el) return;

            const anchor = el.type === 'checkbox' || el.type === 'radio' ? el.closest('div') : el;
            if (!anchor || !anchor.parentNode) return;

            const note = document.createElement('div');
            note.className = 'ai-note';
            note.innerHTML = `<i class="fa-solid fa-circle-exclamation mr-1"></i>${text}`;
            anchor.parentNode.insertBefore(note, anchor.nextSibling);
        },

        clearNotes() {
            document.querySelectorAll('.ai-note').forEach(n => n.remove());
            document.querySelectorAll('.ai-filled-group').forEach(n => n.classList.remove('ai-filled-group'));
        },

        jumpTo(step) {
            window.dispatchEvent(new CustomEvent('goto-step', { detail: { step } }));
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
    };
}
</script>
