<?php

namespace App\Providers;

use App\Models\StationaryRequest;
use App\Policies\StationaryRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Register policies
        Gate::policy(StationaryRequest::class, StationaryRequestPolicy::class);

        // Define gates for custom authorization
        Gate::define('approve-request', function ($user, $request) {
            return (new StationaryRequestPolicy())->approve($user, $request);
        });

        Gate::define('reject-request', function ($user, $request) {
            return (new StationaryRequestPolicy())->reject($user, $request);
        });
    }

    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        // Auto-discovers policies in App\Policies namespace
    }
}
