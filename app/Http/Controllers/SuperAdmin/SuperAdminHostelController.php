<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;

class SuperAdminHostelController extends Controller
{
    public function index()
    {
        $hostels = Hostel::with('owner')->latest()->get();
        return view('super-admin.hostels.index', compact('hostels'));
    }

    public function show(Hostel $hostel)
    {
        $hostel->load('owner', 'rooms', 'beds');
        return view('super-admin.hostels.show', compact('hostel'));
    }

    public function destroy(Hostel $hostel)
    {
        $hostel->delete();
        return redirect()->route('super-admin.hostels.index')
            ->with('success', 'Hostel supprimé de la plateforme.');
    }
}