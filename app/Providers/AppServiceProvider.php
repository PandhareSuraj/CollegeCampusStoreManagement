<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Enums\RequestStatus;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap pagination views
        Paginator::useBootstrapFive();
        
        // Configure strict mode for Eloquent relationships
        // Prevents accidental N+1 queries in production
        // Comment out if needed for development
        // Model::shouldBeStrict(!$this->app->isProduction());
    }
}
