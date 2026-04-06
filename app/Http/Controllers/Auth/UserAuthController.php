<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserAuthController extends Controller
{
    public function create(): View
    {
    return view('auth.user.login'); // ← corrigé
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::guard('user')->attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Les identifiants sont incorrects.'])
                ->onlyInput('email');
        }

        $user = Auth::guard('user')->user();

        if ($user->status !== 'active') {
            Auth::guard('user')->logout();
            return back()
                ->withErrors(['email' => 'Ce compte utilisateur est inactif.'])
                ->onlyInput('email');
        }

        // Vérifier qu'il a au moins un hostel actif
        $pivot = $user->hostels()->wherePivot('status', 'active')->first();

        if (! $pivot) {
            Auth::guard('user')->logout();
            return back()
                ->withErrors(['email' => 'Votre compte n\'est affecté à aucun hostel actif.'])
                ->onlyInput('email');
        }

        session(['staff_hostel_id' => $pivot->id]);

        $request->session()->regenerate();

        return $this->redirectByRole($pivot->pivot->role);
    }

    protected function redirectByRole(?string $role): RedirectResponse
    {
        return match ($role) {
            'manager'   => redirect()->route('manager.dashboard'),
            'financial' => redirect()->route('staff.financial.dashboard'),
            default     => redirect()->route('staff.dashboard'),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('user')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }
}