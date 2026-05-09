<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user     = Auth::guard('user')->user();
        $hostelId = session('staff_hostel_id');

        $activeHostel = $user->hostels()
            ->where('hostels.id', $hostelId)
            ->wherePivot('status', 'active')
            ->first();

        if (!$activeHostel) {
            Auth::guard('user')->logout();
            return redirect()->route('user.login')
                ->withErrors(['email' => 'Aucun hostel actif associé à votre compte.']);
        }

        $role = $activeHostel->pivot->role;

        return view('staff.dashboard', compact('user', 'activeHostel', 'role'));
    }
}