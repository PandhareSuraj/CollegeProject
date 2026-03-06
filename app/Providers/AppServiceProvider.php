<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
        $this->registerEventListeners();
        // Register custom auth provider for multi-model authentication
        \Illuminate\Support\Facades\Auth::provider('multi', function ($app, array $config) {
            return new \App\Auth\MultiModelUserProvider();
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }

    /**
     * Register event listeners for requests and approvals.
     */
    protected function registerEventListeners(): void
    {
        Event::listen(
            \App\Events\RequestApproved::class,
            \App\Listeners\SendRequestApprovedNotification::class,
        );

        Event::listen(
            \App\Events\RequestRejected::class,
            \App\Listeners\SendRequestRejectedNotification::class,
        );

        Event::listen(
            \App\Events\RequestSupplied::class,
            \App\Listeners\SendRequestSuppliedNotification::class,
        );
    }
}
