<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\Reservation;

class SuperAdminHostelController extends Controller
{
    // ── Liste de tous les hostels de la plateforme ───────────────
    public function index()
    {
        $hostels = Hostel::with('owner')
            ->withCount(['rooms'])
            ->latest()
            ->paginate(20);

        return view('super-admin.hostels.index', compact('hostels'));
    }

    // ── Dashboard d'un hostel (activités, stats) ─────────────────
    // Super admin voit tout : réservations, paiements, équipe — en lecture seule
    public function show(Hostel $hostel)
    {
        $hostel->load('owner', 'rooms.beds');

        $stats = [
            'total_rooms'         => $hostel->rooms()->count(),
            'total_beds'          => $hostel->rooms()->withCount('beds')->get()->sum('beds_count'),
            'active_reservations' => \App\Models\Reservation::where('hostel_id', $hostel->id)
                                        ->whereIn('status', ['confirmed', 'pending'])
                                        ->count(),
            'total_reservations'  => \App\Models\Reservation::where('hostel_id', $hostel->id)->count(),
            'team_count'          => $hostel->users()->count(),
        ];

        return view('super-admin.hostels.show', compact('hostel', 'stats'));
    }

    // ── Activer / Désactiver un hostel ───────────────────────────
    // 🔒 Un hostel désactivé n'est plus accessible par l'owner
    public function toggle(Hostel $hostel)
    {
        $hostel->update(['is_active' => !($hostel->is_active ?? true)]);
        $status = $hostel->is_active ? 'activé' : 'désactivé';

        return back()->with('success', "Hostel « {$hostel->name} » {$status}.");
    }

    // ── Suppression hostel ───────────────────────────────────────
    public function destroy(Hostel $hostel)
    {
        $name = $hostel->name;
        $hostel->delete();

        return redirect()->route('super-admin.hostels.index')
            ->with('success', "Hostel « {$name} » supprimé de la plateforme.");
    }
}