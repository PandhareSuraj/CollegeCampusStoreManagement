<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Enums\RequestStatus;
use App\Policies\StationaryRequestPolicy;
use App\Policies\OrderPolicy;
use App\Policies\UserPolicy;
use App\Models\StationaryRequest;
use App\Models\Order;
use App\Models\User;
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
        
        // Register Model Policies
        $this->registerPolicies();
    }

    /**
     * Register authorization policies
     */
    protected function registerPolicies(): void
    {
        // Register model policies
        $this->app['auth']->policy(StationaryRequest::class, StationaryRequestPolicy::class);
        $this->app['auth']->policy(Order::class, OrderPolicy::class);
        $this->app['auth']->policy(User::class, UserPolicy::class);
    }
}
