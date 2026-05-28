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

    public function __construct(
        public Experience $experience,
        public int $limit = 50
    ) {}

    public function handle(SchedulingEngine $schedulingEngine)
    {
        // Clear existing recurring instances
        $this->experience->recurringRules()
            ->where('is_cancelled', false)
            ->delete();

        // Generate new dates
        $dates = $schedulingEngine->generateRecurringDates($this->experience, $this->limit);

        // Log or store the generated dates
        \Log::info("Generated {$this->limit} recurring dates for retreat {$this->experience->id}");
    }
}
