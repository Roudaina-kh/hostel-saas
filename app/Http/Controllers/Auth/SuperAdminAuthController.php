<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SuperAdminAuthController extends Controller
{
    public function create(): View
    {
        return view('auth.super-admin.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::guard('super_admin')->attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Les identifiants sont incorrects.'])
                ->onlyInput('email');
        }

        $superAdmin = Auth::guard('super_admin')->user();

        if (! $superAdmin->is_active) {
            Auth::guard('super_admin')->logout();
            return back()
                ->withErrors(['email' => 'Ce compte super admin est désactivé.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $superAdmin->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        return redirect()->intended(route('super-admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('super_admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('super-admin.login');
    }
}