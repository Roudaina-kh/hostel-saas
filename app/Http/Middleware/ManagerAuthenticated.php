<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::guard('user')->check()) {
            return redirect()->route('user.login');
        }

        $user = Auth::guard('user')->user();

        $hostelId = session('staff_hostel_id');

        if (! $hostelId) {
            Auth::guard('user')->logout();
            return redirect()->route('user.login')->with('error', 'Aucun hostel sélectionné.');
        }

        $pivot = $user->hostels()
            ->where('hostels.id', $hostelId)
            ->wherePivot('status', 'active')
            ->first();

        if (! $pivot || $pivot->pivot->role !== 'manager') {
            Auth::guard('user')->logout();
            return redirect()->route('user.login')->with('error', 'Accès réservé aux managers.');
        }

        view()->share('managerHostel', $pivot);
        view()->share('currentManager', $user);

        return $next($request);
    }
}