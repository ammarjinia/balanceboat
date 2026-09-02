{{-- "Structure with AI" review modal. Two-step flow matching RetreatContentStructuringService:
     preview (AJAX, never writes anything) -> the reviewer checks only the rows they want -> apply
     (AJAX, writes only the checked fields). Experience-field rows that have a matching textarea
     on THIS page (by name attribute) are synced into the form live after saving, including into
     the TinyMCE instance if that field is a rich editor, so the wizard's own Update button won't
     later overwrite the AI content with stale form state. Center fields and any experience field
     with no on-page input (food_overview, schedule) are saved straight to the database — this page
     has nothing to sync them into, so the row is labeled accordingly instead of silently doing
     nothing visible. --}}
<div x-data="aiStructureModal({{ $experience->id }})"
     x-init="init()"
     @open-ai-structure.window="open()"
     x-show="visible"
     x-cloak
     class="fixed inset-0 z-[200] flex items-start justify-center overflow-y-auto py-8 px-4"
     style="display:none;">
    <div class="fixed inset-0 bg-slate-900/60" @click="close()"></div>

    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-3xl my-auto">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
            <div>
                <h2 class="text-lg font-serif text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles text-purple-500"></i>
                    Structure with AI
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Reorganizes your existing text into clean, search-friendly sections and drafts an SEO title &amp; description — it never invents new facts, and nothing changes until you check a row and hit Save.</p>
            </div>
            <button type="button" @click="close()" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="px-6 py-5 max-h-[65vh] overflow-y-auto space-y-5">
            <template x-if="state === 'loading'">
                <div class="flex flex-col items-center justify-center py-16 text-slate-400 gap-3">
                    <i class="fa-solid fa-circle-notch fa-spin text-2xl"></i>
                    <p class="text-xs">Reading your retreat's content…</p>
                </div>
            </template>

            <template x-if="state === 'error'">
                <div class="bg-red-50 border border-red-200 text-red-700 text-xs rounded-2xl px-5 py-4">
                    <p class="font-semibold mb-1">Couldn't structure this content.</p>
                    <p x-text="errorMessage"></p>
                </div>
            </template>

            <template x-if="state === 'empty'">
                <div class="text-center py-16 text-slate-400 text-xs">
                    No changes to suggest — every field is either already clean or has no source text yet.
                </div>
            </template>

            <template x-if="state === 'ready'">
                <div class="space-y-5">
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer select-none">
                        <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-slate-300 text-purple-600">
                        Select all suggested changes
                    </label>

                    <template x-for="row in rows" :key="row.key">
                        <div class="border border-slate-200 rounded-2xl p-4">
                            <label class="flex items-start gap-3 cursor-pointer select-none">
                                <input type="checkbox" x-model="row.accepted" class="mt-1 rounded border-slate-300 text-purple-600">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-xs font-bold text-slate-800" x-text="row.label"></span>
                                        <span class="text-[10px] uppercase tracking-wide px-2 py-0.5 rounded-full bg-slate-100 text-slate-500" x-text="row.scopeLabel"></span>
                                        <span x-show="!row.onPage" class="text-[10px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                            Saved directly — not shown on this page
                                        </span>
                                    </div>
                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                        <div>
                                            <p class="text-[10px] uppercase text-slate-400 font-semibold mb-1">Before</p>
                                            <div class="text-slate-500 bg-slate-50 rounded-xl p-2.5 max-h-28 overflow-y-auto whitespace-pre-line" x-text="row.before || '(empty)'"></div>
                                        </div>
                                        <div>
                                            <p class="text-[10px] uppercase text-purple-400 font-semibold mb-1">After</p>
                                            <div class="text-slate-700 bg-purple-50 rounded-xl p-2.5 max-h-28 overflow-y-auto whitespace-pre-line" x-text="row.after"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </template>

                    <template x-if="amenitySuggestions.length">
                        <div class="border border-slate-200 rounded-2xl p-4">
                            <label class="flex items-start gap-3 cursor-pointer select-none">
                                <input type="checkbox" x-model="amenitiesAccepted" class="mt-1 rounded border-slate-300 text-purple-600">
                                <div>
                                    <span class="text-xs font-bold text-slate-800">Amenities</span>
                                    <span class="text-[10px] uppercase tracking-wide px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 ml-2">Center</span>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Add: <span class="text-slate-700 font-medium" x-text="amenitySuggestions.map(a => a.name).join(', ')"></span>
                                    </p>
                                </div>
                            </label>
                        </div>
                    </template>
                    <template x-if="amenityUnmatched.length">
                        <p class="text-[11px] text-slate-400 px-1">
                            Mentioned but not in your amenities list (not added): <span x-text="amenityUnmatched.join(', ')"></span>
                        </p>
                    </template>
                </div>
            </template>
        </div>

        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-200 bg-slate-50 rounded-b-3xl">
            <p class="text-[11px] text-slate-400" x-show="state === 'ready'">
                <span x-text="acceptedCount()"></span> change(s) selected
            </p>
            <div class="flex items-center gap-2 ml-auto">
                <button type="button" @click="close()" class="px-4 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">Cancel</button>
                <button type="button" @click="save()" x-show="state === 'ready'" :disabled="saving || acceptedCount() === 0"
                        class="px-5 py-2.5 bg-purple-600 text-white rounded-2xl text-xs font-semibold hover:bg-purple-700 active:scale-95 transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-2">
                    <i class="fa-solid fa-circle-notch fa-spin" x-show="saving"></i>
                    <span x-text="saving ? 'Saving…' : 'Save selected changes'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function aiStructureModal(experienceId) {
    return {
        experienceId,
        visible: false,
        state: 'loading', // loading | ready | empty | error
        errorMessage: '',
        rows: [],
        amenitySuggestions: [],
        amenityUnmatched: [],
        amenitiesAccepted: true,
        selectAll: true,
        saving: false,

        init() {},

        open() {
            this.visible = true;
            this.state = 'loading';
            this.rows = [];
            this.amenitySuggestions = [];
            this.amenityUnmatched = [];

            fetch(`/center-panel/experiences/${this.experienceId}/structure-content/preview`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
            })
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        this.state = 'error';
                        this.errorMessage = data.error;
                        return;
                    }
                    this.buildRows(data.diff);
                })
                .catch(e => {
                    this.state = 'error';
                    this.errorMessage = e.message || 'Request failed.';
                });
        },

        close() {
            this.visible = false;
        },

        buildRows(diff) {
            const rows = [];
            const onPageFields = this.onPageExperienceFields();

            for (const [scope, scopeLabel] of [['experience', 'Experience'], ['center', 'Center']]) {
                for (const [field, row] of Object.entries(diff[scope] || {})) {
                    const before = (row.before || '').trim();
                    const after = (row.after || '').trim();
                    if (!after || after === before) continue;
                    rows.push({
                        key: `${scope}.${field}`,
                        scope, field, scopeLabel,
                        label: row.label, type: row.type,
                        before, after,
                        accepted: true,
                        onPage: scope === 'experience' && onPageFields.includes(field),
                    });
                }
            }

            this.rows = rows;
            this.amenitySuggestions = diff.amenities?.suggested || [];
            this.amenityUnmatched = diff.amenities?.unmatched || [];
            this.selectAll = true;

            this.state = (rows.length || this.amenitySuggestions.length) ? 'ready' : 'empty';
        },

        onPageExperienceFields() {
            return Array.from(document.querySelectorAll('#experienceForm [name]'))
                .map(el => el.getAttribute('name'))
                .filter(Boolean);
        },

        toggleAll() {
            this.rows.forEach(r => r.accepted = this.selectAll);
            this.amenitiesAccepted = this.selectAll;
        },

        acceptedCount() {
            return this.rows.filter(r => r.accepted).length + (this.amenitiesAccepted && this.amenitySuggestions.length ? 1 : 0);
        },

        save() {
            const payload = { experience: {}, center: {}, amenity_ids: [] };
            this.rows.filter(r => r.accepted).forEach(r => { payload[r.scope][r.field] = r.after; });
            if (this.amenitiesAccepted) {
                payload.amenity_ids = this.amenitySuggestions.map(a => a.id);
            }

            this.saving = true;
            fetch(`/center-panel/experiences/${this.experienceId}/structure-content/apply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify(payload),
            })
                .then(r => r.json())
                .then(data => {
                    this.saving = false;
                    if (data.error) {
                        this.state = 'error';
                        this.errorMessage = data.error;
                        return;
                    }
                    this.syncOnPageFields(payload.experience);
                    this.close();
                    if (window.showToast) {
                        window.showToast('Content structured and saved.');
                    } else {
                        alert('Content structured and saved.');
                    }
                })
                .catch(e => {
                    this.saving = false;
                    this.state = 'error';
                    this.errorMessage = e.message || 'Save failed.';
                });
        },

        // Reflects just-saved experience fields into this page's inputs (and their TinyMCE
        // instance, if any) so the wizard's own Update button doesn't later resubmit stale values
        // over the top of what the AI pass just saved.
        syncOnPageFields(experienceValues) {
            Object.entries(experienceValues).forEach(([name, value]) => {
                const el = document.querySelector(`#experienceForm [name="${name}"]`);
                if (!el) return;
                el.value = value;
                if (window.tinymce) {
                    const editor = window.tinymce.editors.find(ed => ed.getElement() === el);
                    if (editor) editor.setContent(value);
                }
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            });
        },
    };
}
</script>
