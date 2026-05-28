<?php

namespace App\Events;

use App\Models\Experience;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RetreatDrafted
{
    use Dispatchable, SerializesModels;

    public function __construct(public Experience $retreat) {}
}
