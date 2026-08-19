<?php

namespace App\Console\Commands;

use App\Experiences;
use App\Services\RetreatContentStructuringService;
use Illuminate\Console\Command;

/**
 * php artisan retreat:structure-content 42            # preview one experience (dry run)
 * php artisan retreat:structure-content 42 --apply     # preview, then ask to confirm and save
 * php artisan retreat:structure-content --all          # preview every non-draft experience
 * php artisan retreat:structure-content --all --apply --force   # apply to all, no per-record prompt
 *
 * Never writes to the database unless --apply is passed, and even then asks for confirmation per
 * record unless --force is also given — see RetreatContentStructuringService's class doc for why
 * (an LLM producing a bad pass for one field shouldn't be able to silently clobber real content).
 */
class StructureRetreatContent extends Command
{
    protected $signature = 'retreat:structure-content
        {experience? : Experience ID or slug to process}
        {--all : Process every non-draft experience instead of a single one}
        {--apply : Save the AI-structured content (omit for a dry-run preview only)}
        {--force : With --apply, skip the per-record confirmation prompt}';

    protected $description = 'Use AI to clean up and restructure a retreat\'s raw content into the fields experience_detail.blade.php expects';

    public function handle(RetreatContentStructuringService $service): int
    {
        $experiences = $this->resolveExperiences();
        if ($experiences->isEmpty()) {
            $this->error('No matching experience found.');
            return self::FAILURE;
        }

        $apply = (bool) $this->option('apply');
        $force = (bool) $this->option('force');

        foreach ($experiences as $experience) {
            $this->newLine();
            $this->line("<fg=cyan>━━━ #{$experience->id} — {$experience->name} ━━━</>");

            try {
                $diff = $service->preview($experience);
            } catch (\Throwable $e) {
                $this->error("  Skipped: {$e->getMessage()}");
                continue;
            }

            $accepted = $this->renderDiffAndCollectAccepted($diff);

            if (empty($accepted['experience']) && empty($accepted['center']) && empty($accepted['amenity_ids'])) {
                $this->comment('  Nothing to change.');
                continue;
            }

            if (!$apply) {
                $this->comment('  (dry run — pass --apply to save)');
                continue;
            }

            if (!$force && !$this->confirm('  Save these changes?', false)) {
                $this->comment('  Skipped.');
                continue;
            }

            $service->apply($experience, $accepted);
            $this->info('  Saved.');
        }

        return self::SUCCESS;
    }

    private function resolveExperiences()
    {
        if ($this->option('all')) {
            return Experiences::where('is_draft', 0)->orderBy('id')->get();
        }

        $id = $this->argument('experience');
        if (!$id) {
            $this->error('Pass an experience ID/slug, or use --all.');
            return collect();
        }

        $query = Experiences::query();
        $query->where(is_numeric($id) ? 'id' : 'slug', $id);
        $found = $query->first();

        return $found ? collect([$found]) : collect();
    }

    /**
     * Prints every field's before/after and returns only the fields whose "after" is non-empty
     * and actually differs from "before" — i.e. what a --apply run would save. This mirrors what
     * the center-panel review screen shows, just as plain console output.
     */
    private function renderDiffAndCollectAccepted(array $diff): array
    {
        $accepted = ['experience' => [], 'center' => [], 'amenity_ids' => []];

        foreach (['experience' => 'Experience', 'center' => 'Center'] as $scope => $scopeLabel) {
            foreach ($diff[$scope] ?? [] as $field => $row) {
                $before = trim((string) ($row['before'] ?? ''));
                $after = trim((string) ($row['after'] ?? ''));
                if ($after === '' || $after === $before) {
                    continue;
                }
                $this->line("  <fg=yellow>{$scopeLabel} · {$row['label']}</>");
                $this->line('    before: ' . $this->truncate($before ?: '(empty)'));
                $this->line('    after:  ' . $this->truncate($after));
                $accepted[$scope][$field] = $row['after'];
            }
        }

        $amenities = $diff['amenities'] ?? [];
        if (!empty($amenities['suggested'])) {
            $names = collect($amenities['suggested'])->pluck('name')->implode(', ');
            $this->line("  <fg=yellow>Center · Amenities (suggested add)</> {$names}");
            $accepted['amenity_ids'] = collect($amenities['suggested'])->pluck('id')->all();
        }
        if (!empty($amenities['unmatched'])) {
            $this->line('  <fg=gray>Amenities mentioned but not in the Amenities list (not applied): ' . implode(', ', $amenities['unmatched']) . '</>');
        }

        return $accepted;
    }

    private function truncate(string $text, int $length = 140): string
    {
        $text = preg_replace('/\s+/', ' ', $text);
        return mb_strlen($text) > $length ? mb_substr($text, 0, $length) . '…' : $text;
    }
}
