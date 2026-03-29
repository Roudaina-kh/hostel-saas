<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnifiedLoginController extends Controller
{
    public function showForm()
    {
        // Redirection si déjà connecté
        if (Auth::guard('super_admin')->check()) return redirect()->route('super-admin.dashboard');
        if (Auth::check())                       return redirect()->route('dashboard');
        if (Auth::guard('staff')->check()) {
            $user = Auth::guard('staff')->user();
            // Résoudre le hostel actif de la session
            $hostelId = session('staff_hostel_id');
            if ($hostelId) {
                $role = $user->roleInHostel($hostelId);
                return $this->redirectByRole($role);
            }
            return redirect()->route('staff.dashboard');
        }

        return view('auth.unified-login');
    }

    /**
     * Traite la tentative de connexion pour tous les rôles.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        // 1. Tentative Super Admin
        if (Auth::guard('super_admin')->attempt($credentials, $remember)) {
            if (!Auth::guard('super_admin')->user()->is_active) {
                Auth::guard('super_admin')->logout();
                return back()->withErrors(['email' => 'Compte Super Admin désactivé.']);
            }
            $request->session()->regenerate();
            return redirect()->route('super-admin.dashboard');
        }

        // 2. Tentative Owner (Guard par défaut 'web')
        if (Auth::attempt($credentials, $remember)) {
            $owner = Auth::user();
            $request->session()->regenerate();

            if ($owner->hostels()->count() === 0) {
                return redirect()->route('onboarding.create');
            }

            if (!session('hostel_id')) {
                session(['hostel_id' => $owner->hostels()->first()->id]);
            }
            return redirect()->route('dashboard');
        }

        // 3. Tentative Staff / Manager / Financial (Guard 'staff' sur table 'users')
        if (Auth::guard('staff')->attempt($credentials, $remember)) {
            $user = Auth::guard('staff')->user();

            // Vérifier que le user a au moins un hostel actif
            $pivot = $user->hostels()->wherePivot('status', 'active')->first();

            if (!$pivot) {
                Auth::guard('staff')->logout();
                return back()->withErrors(['email' => 'Votre compte est désactivé ou non affecté à un hostel.']);
            }

            // Stocker le hostel actif en session
            session(['staff_hostel_id' => $pivot->id]);

            $request->session()->regenerate();
            return $this->redirectByRole($pivot->pivot->role);
        }

        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.'])->onlyInput('email');
    }

    /**
     * Redirige selon le rôle du user dans le hostel actif.
     */
    protected function redirectByRole(?string $role)
{
    return match ($role) {
        'financial' => redirect()->route('staff.financial.dashboard'),
        'manager'   => redirect()->route('manager.dashboard'),
        'staff'     => redirect()->route('staff.dashboard'),
        default     => redirect()->route('staff.dashboard'),
    };
}
    /**
     * Déconnexion globale.
     */
    public function logout(Request $request)
    {
        Auth::guard('super_admin')->logout();
        Auth::guard('staff')->logout();
        Auth::logout(); // Web guard (owners)

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
