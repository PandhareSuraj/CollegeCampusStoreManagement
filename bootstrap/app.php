<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckApprovalAccess;
use App\Http\Middleware\AllowProviderOnly;
use App\Http\Middleware\AllowAdminOnly;
use App\Http\Middleware\EnsureDepartmentAssigned;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom middleware aliases
        $middleware->alias([
            'role' => CheckRole::class,
            'check-approval-access' => CheckApprovalAccess::class,
            'provider-only' => AllowProviderOnly::class,
            'admin-only' => AllowAdminOnly::class,
            'ensure-department-assigned' => EnsureDepartmentAssigned::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
