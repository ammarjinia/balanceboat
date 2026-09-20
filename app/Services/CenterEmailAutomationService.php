<?php

namespace App\Services;

use App\CenterEmailLog;
use App\Centers;
use App\Jobs\SendCenterAutomationEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

/**
 * The single gate every center automation email passes through.
 *
 * Evaluators (the scheduled commands, and the controllers that fire immediate emails) decide only
 * whether a center *qualifies* for a template. This class decides whether that qualifying center
 * should actually be mailed right now — opt-out, cooldown, per-template caps and the global weekly
 * cap all live here, so no evaluator can accidentally bypass them.
 *
 * Every decision is recorded in center_email_logs, including the refusals ('skipped'), which is
 * what makes the automation debuggable after the fact: you can always answer "why did this partner
 * get / not get this email on this day".
 */
class CenterEmailAutomationService
{
    public function __construct(
        private CenterListingHealthService $health,
    ) {
    }

    /**
     * Queue one automation email for a center, if it passes every guard.
     *
     * @param  array<string, mixed>  $data     Extra view data merged over the health snapshot.
     * @param  bool                  $dryRun   Evaluate and report, but neither log nor send.
     * @return string  One of: queued, disabled, unknown_template, template_disabled, no_owner,
     *                 opted_out, already_sent, cooling_down, max_sends_reached, weekly_cap
     */
    public function send(Centers $center, string $key, array $data = [], bool $dryRun = false): string
    {
        $template = config("center_emails.templates.{$key}");

        if (!$template) {
            Log::warning("Center email automation: unknown template '{$key}'");
            return 'unknown_template';
        }

        // A dry run deliberately evaluates past the master switch — the whole point of running one
        // before go-live is to see what *would* be sent while sending is still turned off.
        if (!config('center_emails.enabled') && !$dryRun) {
            return 'disabled';
        }

        if (empty($template['enabled'])) {
            return 'template_disabled';
        }

        $snapshot = $this->health->snapshot($center);
        $owner    = $snapshot['owner'];

        if (!$owner) {
            return 'no_owner';
        }

        $transactional = !empty($template['transactional']);

        if (!$transactional && $center->email_automation_opt_out) {
            return 'opted_out';
        }

        // Refusals are returned, not logged. A daily cron re-evaluates every center, so writing a
        // row per refusal would add tens of thousands of rows a day that say nothing a re-run of
        // the command with --dry-run can't tell you. The commands report refusal counts instead.
        if ($reason = $this->frequencyBlock($center, $key, $template, $transactional)) {
            return $reason;
        }

        if ($dryRun) {
            return 'queued';
        }

        $payload = $this->payload($center, $key, $template, $snapshot, $data);

        $log = CenterEmailLog::create([
            'center_id'       => $center->id,
            'user_id'         => $owner->id,
            'email_key'       => $key,
            'recipient_email' => config('center_emails.redirect_to') ?: $owner->email,
            'subject'         => $payload['subject'],
            'status'          => 'queued',
            'meta'            => $payload['meta'],
        ]);

        SendCenterAutomationEmail::dispatch($log->id, $payload);

        return 'queued';
    }

    /**
     * Frequency guards, in order of specificity.
     *
     * @return string|null  Refusal code, or null when the send may proceed.
     */
    private function frequencyBlock(Centers $center, string $key, array $template, bool $transactional): ?string
    {
        $sent = CenterEmailLog::delivered()->where('center_id', $center->id)->where('email_key', $key);

        if (!empty($template['once'])) {
            // A once-only template has nothing else to check: it is the partner's very first
            // contact, so it is never held back by the weekly cap either.
            return (clone $sent)->exists() ? 'already_sent' : null;
        }

        if (!empty($template['max_sends']) && (clone $sent)->count() >= (int) $template['max_sends']) {
            return 'max_sends_reached';
        }

        if (!empty($template['cooldown_days'])) {
            $cutoff = now()->subDays((int) $template['cooldown_days']);

            if ((clone $sent)->where('created_at', '>=', $cutoff)->exists()) {
                return 'cooling_down';
            }
        }

        if (!$transactional) {
            $cap = (int) config('center_emails.weekly_cap', 2);

            $recent = CenterEmailLog::delivered()
                ->where('center_id', $center->id)
                ->where('created_at', '>=', now()->subDays(7))
                ->count();

            if ($cap > 0 && $recent >= $cap) {
                return 'weekly_cap';
            }
        }

        return null;
    }

    /**
     * Everything the queued job needs to render and address the email.
     *
     * Resolved here rather than in the job because route() and config() must be evaluated against
     * the current request/console context, and because the payload is serialized into the queue —
     * keeping it to scalars and arrays avoids dragging Eloquent models through the jobs table.
     *
     * @param  array<string, mixed>  $snapshot
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function payload(Centers $center, string $key, array $template, array $snapshot, array $data): array
    {
        $owner = $snapshot['owner'];

        $replacements = [
            '{center_name}' => $center->name ?: 'your center',
            '{first_name}'  => $snapshot['first_name'],
            '{city}'        => $center->city ?: '',
            '{country}'     => $center->country ?: '',
        ];

        $subject = strtr($template['subject'], $replacements);

        $viewData = array_merge([
            'centerName'    => $center->name ?: 'your center',
            'firstName'     => $snapshot['first_name'],
            'snapshot'      => $this->viewSnapshot($snapshot),
            'emailKey'      => $key,
            'previewText'   => strtr($template['preview'] ?? '', $replacements),
            'footerVariant' => $template['footer'] ?? 'operational',
            'supportEmail'  => config('center_emails.support_email'),
            'dashboardUrl'  => route('center-panel.dashboard'),
            'ctaLabel'      => $template['cta']['label'] ?? 'Go to Your Dashboard',
            'ctaUrl'        => $this->ctaUrl($template),
            'unsubscribeUrl' => empty($template['transactional'])
                ? $this->unsubscribeUrl($center)
                : null,
        ], $data);

        return [
            'key'       => $key,
            'view'      => 'center_panel.emails.automation.' . $template['view'],
            'subject'   => $subject,
            'to'        => config('center_emails.redirect_to') ?: $owner->email,
            'to_name'   => trim(($owner->first_name ?? '') . ' ' . ($owner->last_name ?? '')),
            'view_data' => $viewData,
            'meta'      => $this->meta($snapshot, $data),
        ];
    }

    /**
     * The subset of the health snapshot the Blade templates actually read.
     *
     * The full snapshot holds the Centers and User models, and this array is PHP-serialized into
     * the jobs table — a centers row carries several longtext columns that no email renders, so
     * queueing them would bloat every job and slow the worker for nothing.
     *
     * @param  array<string, mixed>  $snapshot
     * @return array<string, mixed>
     */
    private function viewSnapshot(array $snapshot): array
    {
        return [
            'profile_score'           => $snapshot['profile_score'],
            'profile_missing'         => $snapshot['profile_missing'],
            'pricing_updated_at'      => $snapshot['pricing_updated_at'],
            'availability_updated_at' => $snapshot['availability_updated_at'],
            'gallery_updated_at'      => $snapshot['gallery_updated_at'],
            'open_slots_ahead'        => $snapshot['open_slots_ahead'],
            'retreat_count'           => $snapshot['retreat_count'],
            'inactive_days'           => $snapshot['inactive_days'],
        ];
    }

    private function ctaUrl(array $template): string
    {
        $route = $template['cta']['route'] ?? 'center-panel.dashboard';

        return route($route);
    }

    /**
     * Signed one-click unsubscribe. Signed rather than authenticated because a partner who has
     * stopped logging in is exactly the one most likely to want out, and making them sign in first
     * is how you get marked as spam instead.
     */
    private function unsubscribeUrl(Centers $center): string
    {
        return URL::signedRoute('center-panel.emails.unsubscribe', ['center' => $center->id]);
    }

    /**
     * The trigger values the email was rendered from, kept small enough to store as JSON on every
     * row. This is what lets you reconstruct why a send fired months later.
     *
     * @param  array<string, mixed>  $snapshot
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function meta(array $snapshot, array $data): array
    {
        return array_filter([
            'profile_score'           => $snapshot['profile_score'],
            'retreat_count'           => $snapshot['retreat_count'],
            'accommodation_complete'  => $snapshot['accommodation_complete'],
            'availability_updated_at' => optional($snapshot['availability_updated_at'])->toDateTimeString(),
            'pricing_updated_at'      => optional($snapshot['pricing_updated_at'])->toDateTimeString(),
            'gallery_updated_at'      => optional($snapshot['gallery_updated_at'])->toDateTimeString(),
            'open_slots_ahead'        => $snapshot['open_slots_ahead'],
            'inactive_days'           => $snapshot['inactive_days'],
            'trigger'                 => $data['trigger'] ?? null,
        ], fn ($v) => $v !== null);
    }
}
