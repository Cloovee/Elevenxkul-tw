<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
<<<<<<< HEAD
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
=======
          $middleware->alias([
            'role' => CheckRole::class,
>>>>>>> dde0c152e5195de1c407cab0c885f3f109f41cf6
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
