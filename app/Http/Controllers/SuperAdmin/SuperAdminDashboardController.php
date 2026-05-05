<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\Hostel;
use App\Models\Reservation;
use App\Models\User;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            // Propriétaires
            'total_owners'   => Owner::count(),
            'active_owners'  => Owner::where('is_active', true)->count(),

            // Hostels
            'total_hostels'  => Hostel::count(),
            'active_hostels' => Hostel::where('is_active', true)->count(),

            // Utilisateurs (managers/staff/financial)
            'total_users'    => User::count(),

            // Réservations (lecture seule — super admin supervise)
            'total_reservations' => Reservation::count(),
            'active_reservations'=> Reservation::whereIn('status', ['confirmed', 'pending'])->count(),

            // Récents
            'recent_owners'  => Owner::latest()->take(5)->get(),
            'recent_hostels' => Hostel::with('owner')->latest()->take(5)->get(),
        ];

        return view('super-admin.dashboard', compact('stats'));
    }
}