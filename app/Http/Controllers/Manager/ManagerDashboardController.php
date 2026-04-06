<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Bed;
use App\Models\TentSpace;
use Illuminate\Support\Facades\Auth;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $manager  = Auth::guard('user')->user();
        $hostelId = session('staff_hostel_id');

        if (!$hostelId) {
            return redirect()->route('login')->with('error', 'Aucun hostel sélectionné.');
        }

        // Récupérer l'objet hostel via le pivot
        $hostel = $manager->hostels()
            ->where('hostels.id', $hostelId)
            ->wherePivot('status', 'active')
            ->first();

        if (!$hostel) {
            Auth::guard('user')->logout();
            return redirect()->route('login')->with('error', 'Hostel introuvable.');
        }

        $stats = [
            'total_rooms'      => Room::where('hostel_id', $hostelId)->count(),
            'active_rooms'     => Room::where('hostel_id', $hostelId)->where('status', 'active')->count(),
            'total_beds'       => Bed::where('hostel_id', $hostelId)->count(),
            'maintenance_beds' => Bed::where('hostel_id', $hostelId)->where('maintenance', true)->count(),
            'total_tents'      => TentSpace::where('hostel_id', $hostelId)->count(),
            'rooms'            => Room::where('hostel_id', $hostelId)->latest()->take(5)->get(),
        ];

        return view('manager.dashboard', compact('stats', 'manager', 'hostel'));
    }
}