<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifie que le super admin est connecté via son guard
        if (! Auth::guard('super_admin')->check()) {
            return redirect()->route('login');
        }

        // Vérifie que le compte est actif
        if (! Auth::guard('super_admin')->user()->is_active) {
            Auth::guard('super_admin')->logout();
            return redirect()->route('super-admin.login')
                ->with('error', 'Votre compte Super Admin est désactivé.');
        }

        return $next($request);
    }
}