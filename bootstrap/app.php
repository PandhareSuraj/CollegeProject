<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'department' => \App\Http\Middleware\CheckDepartment::class,
            'check-request' => \App\Http\Middleware\CheckRequestAccess::class,
            'check-approval' => \App\Http\Middleware\CheckApprovalAccess::class,
            'check-provider' => \App\Http\Middleware\CheckProvider::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
