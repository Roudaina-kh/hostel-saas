<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelSelected
{
    public function handle(Request $request, Closure $next)
    {
        $owner = Auth::user();
        $hostelId = session('hostel_id');

        if (! $hostelId) {
            if ($owner->hostels()->count() === 0) {
                return redirect()->route('onboarding.create');
            }
            $hostelId = $owner->hostels()->first()->id;
            session(['hostel_id' => $hostelId]);
        }

        // Utiliser == au lieu de === pour éviter les problèmes int/string
        $hostel = $owner->hostels()->where('id', $hostelId)->first();

        if (! $hostel) {
            session()->forget('hostel_id');
            return redirect()->route('onboarding.create')
                ->with('error', 'Hostel introuvable.');
        }

        view()->share('activeHostel', $hostel);
        view()->share('ownerHostels', $owner->hostels()->get());

        return $next($request);
    }
}