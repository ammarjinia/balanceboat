<?php

namespace App\Console\Commands;

use App\Centers;
use App\Services\CenterEmailAutomationService;
use App\Services\CenterListingHealthService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Shared plumbing for the scheduled center-email evaluators.
 *
 * Subclasses implement evaluate() for a single center and say nothing about batching, eligibility,
 * dry runs or reporting — those are identical across every group and easy to get subtly wrong
 * (forgetting --dry-run in one command is how you mail eleven thousand partners by accident).
 *
 * Eligibility is filtered in SQL rather than in PHP: `centers.user_id` is null for the large
 * majority of the legacy directory listings, and those have no Center Owner to email, so excluding
 * them in the query keeps a run proportional to the number of real partner accounts.
 */
abstract class AbstractCenterEmailCommand extends Command
{
    /**
     * Options every evaluator must declare in its own $signature — $signature is a property, so
     * it can't be composed from a method call:
     *
     *   {--dry-run : Evaluate and report without queueing or sending anything}
     *   {--only=   : Restrict the run to a single template key}
     *   {--center= : Restrict the run to one center id}
     */

    /** @var array<string, int> "template:outcome" => count, for the end-of-run summary */
    protected array $outcomes = [];

    protected bool $dryRun = false;

    protected ?string $onlyKey = null;

    /** Sends queued while evaluating the center currently in hand. */
    protected int $queuedThisCenter = 0;

    public function __construct(
        protected CenterEmailAutomationService $emails,
        protected CenterListingHealthService $health,
    ) {
        parent::__construct();
    }

    /**
     * Decide which emails, if any, this center qualifies for and queue them via $this->queue().
     *
     * @param  array<string, mixed>  $snapshot  From CenterListingHealthService::snapshot()
     */
    abstract protected function evaluate(Centers $center, array $snapshot): void;

    /**
     * Narrow the candidate set before any per-center work happens.
     * Subclasses override to add their own constraints.
     */
    protected function candidates()
    {
        return Centers::query()
            ->whereNotNull('user_id')
            ->whereNull('deleted_at')
            ->where(function ($q) {
                $q->where('is_draft', 0)->orWhereNull('is_draft');
            });
    }

    public function handle(): int
    {
        if (!config('center_emails.enabled') && !$this->option('dry-run')) {
            $this->warn('center_emails.enabled is false — nothing will be sent. Set CENTER_EMAILS_ENABLED=true, or pass --dry-run to preview.');
            return self::SUCCESS;
        }

        $limit     = (int) config('center_emails.batch_limit', 200);
        $dryRun    = (bool) $this->option('dry-run');
        $onlyKey   = $this->option('only');
        $centerArg = $this->option('center');

        $query = $this->candidates();

        if ($centerArg) {
            $query->where('id', (int) $centerArg);
        }

        $queued = 0;

        $query->orderBy('id')->chunkById(100, function ($centers) use (&$queued, $limit, $dryRun, $onlyKey) {
            foreach ($centers as $center) {
                if ($queued >= $limit) {
                    return false;
                }

                $this->dryRun  = $dryRun;
                $this->onlyKey = $onlyKey;
                $this->queuedThisCenter = 0;

                $this->evaluate($center, $this->health->snapshot($center));

                $queued += $this->queuedThisCenter;
            }

            // Snapshots are memoized per center for the duration of one evaluate(); release them
            // between chunks so a full run doesn't accumulate every center in memory.
            $this->health->flush();

            return true;
        });

        $this->report($queued, $dryRun);

        return self::SUCCESS;
    }

    /**
     * Ask the automation service to send, honouring --only and --dry-run, and tally the outcome.
     *
     * @param  array<string, mixed>  $data  Extra view data for this template.
     */
    protected function queue(Centers $center, string $key, array $data = []): void
    {
        if ($this->onlyKey && $this->onlyKey !== $key) {
            return;
        }

        $outcome = $this->emails->send($center, $key, $data, $this->dryRun);

        $this->outcomes["{$key}:{$outcome}"] = ($this->outcomes["{$key}:{$outcome}"] ?? 0) + 1;

        if ($outcome === 'queued') {
            $this->queuedThisCenter++;

            if ($this->output->isVerbose()) {
                $this->line(sprintf(
                    '  %s <info>%s</info> → center #%d %s',
                    $this->dryRun ? '[dry-run]' : '[queued] ',
                    $key,
                    $center->id,
                    $center->name
                ));
            }
        }
    }

    /**
     * One summary line per run, to the console and the log. This is the observability that
     * replaces writing a database row for every refusal.
     */
    protected function report(int $queued, bool $dryRun): void
    {
        ksort($this->outcomes);

        $this->newLine();
        $this->info(sprintf(
            '%s — %d email%s %s.',
            $this->getName(),
            $queued,
            $queued === 1 ? '' : 's',
            $dryRun ? 'would be queued' : 'queued'
        ));

        if ($this->outcomes) {
            $rows = [];
            foreach ($this->outcomes as $label => $count) {
                [$key, $outcome] = explode(':', $label, 2);
                $rows[] = [$key, $outcome, $count];
            }
            $this->table(['Template', 'Outcome', 'Centers'], $rows);
        }

        Log::info('Center email automation run', [
            'command'  => $this->getName(),
            'dry_run'  => $dryRun,
            'queued'   => $queued,
            'outcomes' => $this->outcomes,
        ]);
    }
}
