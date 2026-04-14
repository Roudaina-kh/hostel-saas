<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ── Alias des middlewares personnalisés ──────────────
        $middleware->alias([
            'hostel.selected'  => \App\Http\Middleware\HostelSelected::class,
            'manager.auth'     => \App\Http\Middleware\ManagerAuthenticated::class,
            'super_admin.auth' => \App\Http\Middleware\SuperAdminAuthenticated::class,
        ]);

        // ── Redirection par guard quand non authentifié ──────
        // Sans ceci, Laravel cherche route('login') qui n'existe plus
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('super-admin/*')) {
                return route('super-admin.login');
            }
            if ($request->is('user/*') || $request->is('manager/*') || $request->is('staff/*')) {
                return route('user.login');
            }
            return route('owner.login');
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();