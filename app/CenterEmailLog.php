<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * One row per automation email queued for a center.
 *
 * @see \App\Services\CenterEmailAutomationService
 */
class CenterEmailLog extends Model
{
    protected $table = 'center_email_logs';

    protected $guarded = ['id'];

    protected $casts = [
        'meta'    => 'array',
        'sent_at' => 'datetime',
    ];

    public function center()
    {
        return $this->belongsTo(Centers::class, 'center_id');
    }

    /**
     * Sends that actually reached (or are on their way to) a partner's inbox.
     * 'skipped' rows are recorded for observability and must not count towards caps.
     */
    public function scopeDelivered($query)
    {
        return $query->whereIn('status', ['queued', 'sent']);
    }
}
