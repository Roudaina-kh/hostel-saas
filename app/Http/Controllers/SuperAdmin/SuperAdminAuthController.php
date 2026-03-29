<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminAuthController extends Controller
{
    public function showLogin()
    {
        // Redirige si déjà connecté
        if (Auth::guard('super_admin')->check()) {
            return redirect()->route('super-admin.dashboard');
        }
        return view('super-admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (Auth::guard('super_admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $superAdmin = Auth::guard('super_admin')->user();

            // Vérifier que le compte est actif
            if (! $superAdmin->is_active) {
                Auth::guard('super_admin')->logout();
                return back()->withErrors([
                    'email' => 'Votre compte Super Admin est désactivé.',
                ]);
            }

            // Enregistrer dernière connexion
            $superAdmin->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            return redirect()->route('super-admin.dashboard')
                ->with('success', 'Bienvenue, ' . $superAdmin->name . ' !');
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('super_admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('super-admin.login')
            ->with('success', 'Déconnexion réussie.');
    }
}