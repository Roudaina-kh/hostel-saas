<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerLoginController extends Controller
{
    public function showForm()
    {
        return view('auth.owner-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $owner = Auth::user();

            // Redirige vers onboarding si aucun hostel
            if ($owner->hostels()->count() === 0) {
                return redirect()->route('onboarding.create');
            }

            // Restaure le dernier hostel actif ou prend le premier
            if (! session('hostel_id')) {
                session(['hostel_id' => $owner->hostels()->first()->id]);
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}