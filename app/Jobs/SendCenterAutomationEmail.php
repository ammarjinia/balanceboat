<?php

namespace App\Jobs;

use App\CenterEmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Delivers one center automation email and closes out its center_email_logs row.
 *
 * Queued so a scheduled run that qualifies two hundred centers doesn't hold the cron process open
 * on two hundred sequential SMTP handshakes, and so a Brevo outage retries instead of silently
 * dropping the batch.
 *
 * The payload is plain arrays rather than models: it is serialized into the jobs table, and a
 * Centers row carries a lot of longtext this job has no use for.
 */
class SendCenterAutomationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    /** Back off a minute, then five, before giving up — most SMTP failures are transient. */
    public $backoff = [60, 300];

    /**
     * @param  array<string, mixed>  $payload  Built by CenterEmailAutomationService::payload()
     */
    public function __construct(
        public int $logId,
        public array $payload,
    ) {
    }

    public function handle(): void
    {
        $log = CenterEmailLog::find($this->logId);

        // The log row is the source of truth for "has this been sent". If it vanished, or another
        // attempt already completed it, don't send a duplicate.
        if (!$log || $log->status === 'sent') {
            return;
        }

        $payload = $this->payload;

        Mail::send($payload['view'], $payload['view_data'], function ($message) use ($payload) {
            $message->to($payload['to'], $payload['to_name'] ?: null)
                    ->subject($payload['subject']);

            // One-click unsubscribe. Brevo and every major inbox provider surface this header as
            // a native "unsubscribe" control, which keeps complaints away from the spam button.
            if (!empty($payload['view_data']['unsubscribeUrl'])) {
                $message->getHeaders()->addTextHeader(
                    'List-Unsubscribe',
                    '<' . $payload['view_data']['unsubscribeUrl'] . '>'
                );
                $message->getHeaders()->addTextHeader(
                    'List-Unsubscribe-Post',
                    'List-Unsubscribe=One-Click'
                );
            }
        });

        $log->update(['status' => 'sent', 'sent_at' => now()]);
    }

    public function failed(\Throwable $e): void
    {
        Log::error('Center automation email failed', [
            'log_id' => $this->logId,
            'key'    => $this->payload['key'] ?? null,
            'error'  => $e->getMessage(),
        ]);

        CenterEmailLog::where('id', $this->logId)->update([
            'status' => 'failed',
            'error'  => substr($e->getMessage(), 0, 2000),
        ]);
    }
}
