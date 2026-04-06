<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        if (! Auth::guard('owner')->attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Les identifiants sont incorrects.'])
                ->onlyInput('email');
        }

        $owner = Auth::guard('owner')->user();

        if ($owner->status !== 'active') {
            Auth::guard('owner')->logout();
            return back()
                ->withErrors(['email' => 'Ce compte propriétaire est inactif.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // Onboarding si aucun hostel
        if ($owner->hostels()->count() === 0) {
            return redirect()->route('onboarding.create');
        }

        // Pré-sélectionner le premier hostel
        if (! session('hostel_id')) {
            session(['hostel_id' => $owner->hostels()->first()->id]);
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