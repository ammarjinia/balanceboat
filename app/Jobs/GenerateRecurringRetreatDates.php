<?php

namespace App\Jobs;

use App\Models\Experience;
use App\Services\SchedulingEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateRecurringRetreatDates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(SchedulingEngine $schedulingEngine)
    {
        $experiences = Experience::where('is_recurring', true)
            ->where('is_draft', false)
            ->get();

        foreach ($experiences as $experience) {
            $schedulingEngine->generateRecurringDates($experience, 50);
        }
    }
}
