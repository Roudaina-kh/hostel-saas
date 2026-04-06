<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('user')->user();

        // Récupérer le hostel actif depuis la session (stocké lors du login)
        $hostelId = session('staff_hostel_id');

        $hostel = $user->hostels()
            ->where('hostels.id', $hostelId)
            ->wherePivot('status', 'active')
            ->first();

        if (!$hostel) {
            Auth::guard('user')->logout();
            return redirect()->route('login')
                ->with('error', 'Aucun hostel actif trouvé.');
        }

        // Rôle depuis le pivot
        $role = $hostel->pivot->role;

        return view('staff.dashboard', compact('user', 'hostel', 'role'));
    }
}