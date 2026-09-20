<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('sitemap:generate')->weeklyOn(1, '07:00');

/*
|--------------------------------------------------------------------------
| Center panel email automation
|--------------------------------------------------------------------------
|
| The evaluators re-derive every trigger condition from live data on each run, and the
| per-template cooldowns in config/center_emails.php turn these daily/weekly runs into the
| biweekly, monthly, 60-day and yearly cadences the automation spec describes. Nothing here
| sends anything until CENTER_EMAILS_ENABLED=true.
|
| Times are staggered and use withoutOverlapping() because a full pass chunks through every
| partner account and must never have a second copy running behind it.
|
*/

Schedule::command('center-emails:onboarding')
    ->dailyAt('09:15')
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command('center-emails:maintenance')
    ->dailyAt('09:45')
    ->withoutOverlapping()
    ->onOneServer();

// Tuesday rather than Monday: partners act on inventory warnings mid-week, and it keeps this
// clear of the Monday-morning sitemap job.
Schedule::command('center-emails:low-availability')
    ->weeklyOn(2, '10:15')
    ->withoutOverlapping()
    ->onOneServer();