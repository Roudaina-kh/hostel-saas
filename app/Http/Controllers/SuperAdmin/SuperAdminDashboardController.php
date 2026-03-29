<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\Hostel;
use App\Models\SuperAdmin;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_owners'  => Owner::count(),
            'total_hostels' => Hostel::count(),
            'active_owners' => Owner::whereHas('hostels')->count(),
            'recent_owners' => Owner::latest()->take(5)->get(),
            'recent_hostels'=> Hostel::with('owner')->latest()->take(5)->get(),
        ];

        return view('super-admin.dashboard', compact('stats'));
    }
}