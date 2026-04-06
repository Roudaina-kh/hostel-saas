<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        if (Auth::guard('super_admin')->check()) {
            return redirect()->route('super-admin.dashboard');
        }

        if (Auth::guard('owner')->check()) {
            return redirect()->route('dashboard');
        }

        if (Auth::guard('user')->check()) {
            $user     = Auth::guard('user')->user();
            $hostelId = session('staff_hostel_id');

            if ($hostelId) {
                $pivot = $user->hostels()
                    ->where('hostels.id', $hostelId)
                    ->wherePivot('status', 'active')
                    ->first();

                $role = $pivot?->pivot->role;

                return match ($role) {
                    'manager'   => redirect()->route('manager.dashboard'),
                    'financial' => redirect()->route('staff.financial.dashboard'),
                    default     => redirect()->route('staff.dashboard'),
                };
            }

            return redirect()->route('staff.dashboard');
        }

        return view('landing');
    }
}