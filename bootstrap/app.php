<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

require __DIR__.'/../vendor/autoload.php';

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'hostel.selected'    => \App\Http\Middleware\HostelSelected::class,
            'super_admin.auth'   => \App\Http\Middleware\SuperAdminAuthenticated::class,
            'manager.auth'       => \App\Http\Middleware\ManagerAuthenticated::class,
        ]);
    })
    ->create(); // ✅ TRÈS IMPORTANT

// ⚠️ Bind Exception Handler
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

return $app;
