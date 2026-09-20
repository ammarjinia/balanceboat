<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Shared instance so the scheduled evaluator and the automation service it calls reason
        // over the *same* memoized snapshot per center, instead of each recomputing a dozen
        // aggregate queries for every center in the batch.
        $this->app->singleton(\App\Services\CenterListingHealthService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
