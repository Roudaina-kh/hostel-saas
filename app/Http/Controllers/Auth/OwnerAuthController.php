<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OwnerAuthController extends Controller
{
    public function create(): View
    {
        return view('auth.owner-login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // 🔒 Compte supprimé par le super admin (soft delete)
        $deletedOwner = Owner::withTrashed()
            ->where('email', $credentials['email'])
            ->whereNotNull('deleted_at')
            ->first();

        if ($deletedOwner) {
            return back()->withErrors([
                'email' => 'Votre compte a été supprimé par l\'administrateur de la plateforme. Veuillez contacter le support.',
            ])->onlyInput('email');
        }

        if (! Auth::guard('owner')->attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Les identifiants sont incorrects.'])
                ->onlyInput('email');
        }

        $owner = Auth::guard('owner')->user();

        // 🔒 Statut interne inactif
        if ($owner->status !== 'active') {
            Auth::guard('owner')->logout();
            return back()
                ->withErrors(['email' => 'Ce compte propriétaire est inactif.'])
                ->onlyInput('email');
        }

        // 🔒 Désactivé par le Super Admin
        if (! $owner->is_active) {
            Auth::guard('owner')->logout();
            return back()
                ->withErrors(['email' => 'Votre compte a été désactivé par l\'administrateur de la plateforme. Veuillez contacter le support.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // ✅ Traçabilité : dernière connexion
        $owner->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Onboarding si aucun hostel
        if ($owner->hostels()->count() === 0) {
            return redirect()->route('onboarding.create');
        }

        // Pré-sélectionner le premier hostel actif
        if (! session('hostel_id')) {
            $activeHostel = $owner->hostels()->where('is_active', true)->first()
                         ?? $owner->hostels()->first();
            session(['hostel_id' => $activeHostel->id]);
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('owner')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('owner.login');
    }
}