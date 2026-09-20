<?php

namespace App\Console\Commands;

use App\Centers;
use App\Services\CenterListingHealthService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

/**
 * Render automation emails to HTML files (or mail them to yourself) without touching the
 * scheduler, the send log or the frequency caps.
 *
 * Fourteen table-based templates cannot be reviewed by reading Blade, and nobody should be
 * proof-reading partner-facing copy by waiting for a cron to fire. This renders them against a
 * real center's data so the merge fields, the completeness bar and the staleness lines show what a
 * partner would actually see.
 *
 *   php artisan center-emails:preview --all
 *   php artisan center-emails:preview profile_incomplete --center=1234
 *   php artisan center-emails:preview welcome --send=me@example.com
 */
class CenterEmailsPreview extends Command
{
    protected $signature = 'center-emails:preview
                            {key? : Template key from config/center_emails.php}
                            {--all : Render every template}
                            {--center= : Center id to render against (defaults to the newest center with an owner)}
                            {--send= : Email the rendered template to this address instead of writing a file}';

    protected $description = 'Render center automation emails to storage/app/email-previews for review';

    public function handle(CenterListingHealthService $health): int
    {
        $center = $this->resolveCenter();

        if (!$center) {
            $this->error('No center with a Center Owner account found. Pass --center= explicitly.');
            return self::FAILURE;
        }

        $snapshot = $health->snapshot($center);

        if (!$snapshot['owner']) {
            $this->error("Center #{$center->id} has no user with the Owner role, so no email can be addressed to it.");
            return self::FAILURE;
        }

        $keys = $this->option('all')
            ? array_keys(config('center_emails.templates'))
            : array_filter([$this->argument('key')]);

        if (!$keys) {
            $this->error('Pass a template key or --all. Available: ' . implode(', ', array_keys(config('center_emails.templates'))));
            return self::FAILURE;
        }

        $dir = storage_path('app/email-previews');
        File::ensureDirectoryExists($dir);

        $this->line("Rendering against center #{$center->id} — {$center->name}");

        foreach ($keys as $key) {
            $template = config("center_emails.templates.{$key}");

            if (!$template) {
                $this->warn("  unknown template: {$key}");
                continue;
            }

            $view = 'center_panel.emails.automation.' . $template['view'];
            $data = $this->viewData($center, $key, $template, $snapshot);

            if ($to = $this->option('send')) {
                Mail::send($view, $data, function ($m) use ($to, $template, $center) {
                    $m->to($to)->subject('[PREVIEW] ' . strtr($template['subject'], [
                        '{center_name}' => $center->name,
                    ]));
                });
                $this->info("  sent {$key} → {$to}");
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $key . '.html';
            File::put($path, view($view, $data)->render());
            $this->info("  {$key} → {$path}");
        }

        return self::SUCCESS;
    }

    private function resolveCenter(): ?Centers
    {
        if ($id = $this->option('center')) {
            return Centers::find((int) $id);
        }

        return Centers::whereNotNull('user_id')->whereNull('deleted_at')->latest('id')->first();
    }

    /**
     * Mirrors CenterEmailAutomationService::payload()'s view data. Kept here rather than exposing
     * that private method, because a preview deliberately fills in the per-email extras
     * (missing field lists, retreat names) that a real send receives from its evaluator.
     *
     * @param  array<string, mixed>  $template
     * @param  array<string, mixed>  $snapshot
     * @return array<string, mixed>
     */
    private function viewData(Centers $center, string $key, array $template, array $snapshot): array
    {
        return [
            'centerName'     => $center->name ?: 'your center',
            'firstName'      => $snapshot['first_name'],
            'snapshot'       => $snapshot,
            'emailKey'       => $key,
            'previewText'    => $template['preview'] ?? '',
            'footerVariant'  => $template['footer'] ?? 'operational',
            'supportEmail'   => config('center_emails.support_email'),
            'dashboardUrl'   => route('center-panel.dashboard'),
            'ctaLabel'       => $template['cta']['label'] ?? 'Go to Your Dashboard',
            'ctaUrl'         => route($template['cta']['route'] ?? 'center-panel.dashboard'),
            'unsubscribeUrl' => empty($template['transactional']) ? '#preview-unsubscribe' : null,

            // Extras that only some templates use.
            'retreatName'    => 'Sample Retreat Name',
            'missingFields'  => ['Retreat description', 'Itinerary', 'Pricing'],
        ];
    }
}
