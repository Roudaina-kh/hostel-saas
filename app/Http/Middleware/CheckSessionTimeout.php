<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionTimeout
{
    const TIMEOUT_SECONDS = 1800; // 30 minutes

    public function handle(Request $request, Closure $next): Response
    {
        $last = session('_last_activity');

        if ($last !== null && (time() - $last) > self::TIMEOUT_SECONDS) {
            $loginUrl = $this->logoutAllAndGetLoginUrl($request);
            session()->flush();
            session()->regenerate();
            return redirect($loginUrl)->with(
                'error',
                'Votre session a expiré après 30 minutes d\'inactivité. Veuillez vous reconnecter.'
            );
        }

        session(['_last_activity' => time()]);

        return $next($request);
    }

    private function logoutAllAndGetLoginUrl(Request $request): string
    {
        foreach (['owner', 'user', 'super_admin'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $path = $request->path();

        if (str_starts_with($path, 'super-admin')) {
            return route('super-admin.login');
        }

        if (str_starts_with($path, 'manager') || str_starts_with($path, 'staff') || str_starts_with($path, 'user')) {
            return route('user.login');
        }

        return route('owner.login');
    }
}
