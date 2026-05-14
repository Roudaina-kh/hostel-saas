<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Room;
use App\Models\TentSpace;
use Illuminate\Support\Facades\Auth;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $manager  = Auth::guard('user')->user();
        $hostelId = session('staff_hostel_id');

        $hostel = $manager->hostels()
            ->where('hostels.id', $hostelId)
            ->where('hostel_user.status', 'active')
            ->first();

        if (! $hostel) {
            return redirect()->route('login')
                ->with('error', 'Hostel introuvable.');
        }

        $stats = [
            'total_rooms'    => Room::where('hostel_id', $hostelId)->count(),
            'active_rooms'   => Room::where('hostel_id', $hostelId)->where('is_enabled', true)->count(),
            'total_beds'     => Bed::whereHas('room', fn($q) => $q->where('hostel_id', $hostelId))->count(),
            'disabled_beds'  => Bed::whereHas('room', fn($q) => $q->where('hostel_id', $hostelId))->where('is_enabled', false)->count(),
            'total_tents'    => TentSpace::where('hostel_id', $hostelId)->count(),
            'rooms'          => Room::where('hostel_id', $hostelId)->latest()->take(5)->get(),
        ];

        $currentManager = $manager;
        $managerHostel  = $hostel;

        return view('manager.dashboard', compact('stats', 'currentManager', 'managerHostel'));
    }
}