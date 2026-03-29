<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier que le user est connecté via le guard 'staff'
        if (! Auth::guard('staff')->check()) {
            return redirect()->route('login');
        }

        $user = Auth::guard('staff')->user();

        // Récupérer le hostel actif depuis la session
        $hostelId = session('staff_hostel_id');

        if (! $hostelId) {
            Auth::guard('staff')->logout();
            return redirect()->route('login')->with('error', 'Aucun hostel sélectionné.');
        }

        // Vérifier que le user est actif dans ce hostel ET a le rôle 'manager'
        $pivot = $user->hostels()
            ->where('hostels.id', $hostelId)
            ->wherePivot('status', 'active')
            ->first();

        if (! $pivot || $pivot->pivot->role !== 'manager') {
            Auth::guard('staff')->logout();
            return redirect()->route('login')->with('error', 'Accès réservé aux managers.');
        }

        // Partager le hostel actif et le user avec toutes les vues
        view()->share('managerHostel', $pivot);
        view()->share('currentManager', $user);

        return $next($request);
    }
}