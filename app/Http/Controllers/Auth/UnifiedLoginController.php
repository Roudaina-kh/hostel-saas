<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UnifiedLoginController extends Controller
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

        // ── 1. Try OWNER ────────────────────────────────────────────
        if (Auth::guard('owner')->attempt($credentials, $remember)) {
            $owner = Auth::guard('owner')->user();

            if ($owner->status !== 'active') {
                Auth::guard('owner')->logout();
                return back()->withErrors(['email' => 'Ce compte propriétaire est inactif.'])->onlyInput('email');
            }

            $request->session()->regenerate();

            if ($owner->hostels()->count() === 0) {
                return redirect()->route('onboarding.create');
            }

            if (!session('hostel_id')) {
                session(['hostel_id' => $owner->hostels()->first()->id]);
            }

            return redirect()->intended(route('dashboard'));
        }

        // ── 2. Try USER (manager / staff / financial) ────────────────
        if (Auth::guard('user')->attempt($credentials, $remember)) {
            $user = Auth::guard('user')->user();

            if ($user->status !== 'active') {
                Auth::guard('user')->logout();
                return back()->withErrors(['email' => 'Ce compte utilisateur est inactif.'])->onlyInput('email');
            }

            $pivot = $user->hostels()->wherePivot('status', 'active')->first();

            if (!$pivot) {
                Auth::guard('user')->logout();
                return back()->withErrors(['email' => 'Votre compte n\'est affecté à aucun hostel actif.'])->onlyInput('email');
            }

            session(['staff_hostel_id' => $pivot->id]);
            $request->session()->regenerate();

            return match ($pivot->pivot->role) {
                'manager'   => redirect()->route('manager.dashboard'),
                'financial' => redirect()->route('staff.financial.dashboard'),
                default     => redirect()->route('staff.dashboard'),
            };
        }

        // ── 3. Try SUPER ADMIN ───────────────────────────────────────
        if (Auth::guard('super_admin')->attempt($credentials, $remember)) {
            $superAdmin = Auth::guard('super_admin')->user();

            if (!$superAdmin->is_active) {
                Auth::guard('super_admin')->logout();
                return back()->withErrors(['email' => 'Ce compte super admin est désactivé.'])->onlyInput('email');
            }

            $request->session()->regenerate();
            $superAdmin->update(['last_login_at' => now(), 'last_login_ip' => $request->ip()]);

            return redirect()->route('super-admin.dashboard');
        }

        // ── Nothing matched ──────────────────────────────────────────
        return back()
            ->withErrors(['email' => 'Les identifiants sont incorrects.'])
            ->onlyInput('email');
    }
}
