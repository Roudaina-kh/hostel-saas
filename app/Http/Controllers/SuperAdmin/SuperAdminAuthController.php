<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminAuthController extends Controller
{
    // ── GET /super-admin/login ───────────────────────────────────
    public function create()
    {
        if (Auth::guard('super_admin')->check()) {
            return redirect()->route('super-admin.dashboard');
        }
        return view('super-admin.auth.login');
    }

    // ── POST /super-admin/login ──────────────────────────────────
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);

        // Tentative d'authentification avec le guard super_admin
        if (!Auth::guard('super_admin')->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Identifiants incorrects.'])
                ->onlyInput('email');
        }

        $superAdmin = Auth::guard('super_admin')->user();

        // 🔒 Sécurité : vérifier que le compte est actif
        if (!$superAdmin->is_active) {
            Auth::guard('super_admin')->logout();
            return back()->withErrors([
                'email' => 'Votre compte Super Admin est désactivé.',
            ]);
        }

        // 🔒 Régénération de session (protection contre fixation de session)
        $request->session()->regenerate();

        // Traçabilité : enregistre dernière connexion + IP
        $superAdmin->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        return redirect()->route('super-admin.dashboard')
            ->with('success', 'Bienvenue, ' . $superAdmin->name . ' !');
    }

    // ── POST /super-admin/logout ─────────────────────────────────
    public function destroy(Request $request)
    {
        Auth::guard('super_admin')->logout();

        // 🔒 Invalidation + régénération du token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('super-admin.login')
            ->with('success', 'Déconnexion réussie.');
    }
}