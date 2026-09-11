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
            'admin.auth'            => \App\Http\Middleware\AdminAuthenticated::class,
            'vendor.auth'           => \App\Http\Middleware\VendorAuthenticated::class,
            'vendor.approved'       => \App\Http\Middleware\VendorApproved::class,
            'permission'            => \App\Http\Middleware\CheckPermission::class,
            'force.password.change' => \App\Http\Middleware\EnsurePasswordChanged::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
