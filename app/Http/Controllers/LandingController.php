<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    /**
     * Display the landing page (which also contains the login form).
     */
    public function index()
    {
        // Redirection if already logged in (mirroring UnifiedLoginController)
        if (Auth::guard('super_admin')->check()) return redirect()->route('super-admin.dashboard');
        if (Auth::check())                       return redirect()->route('dashboard');
        
        if (Auth::guard('staff')->check()) {
            $user = Auth::guard('staff')->user();
            $hostelId = session('staff_hostel_id');
            
            if ($hostelId) {
                $role = $user->roleInHostel($hostelId);
                return match ($role) {
                    'financial' => redirect()->route('staff.financial.dashboard'),
                    'manager'   => redirect()->route('manager.dashboard'),
                    default     => redirect()->route('staff.dashboard'),
                };
            }
            
            return redirect()->route('staff.dashboard');
        }

        return view('landing');
    }
}
