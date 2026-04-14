<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Room;
use App\Models\TentSpace;
use App\Models\InventoryBlock;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hostelId = session('hostel_id');
        $today    = Carbon::today();

        // ── Lits actifs uniquement ────────────────────────────
        $availableBeds = Bed::whereHas('room', function ($q) use ($hostelId) {
            $q->where('hostel_id', $hostelId);
        })->where('is_enabled', true)->count();  // ← seulement is_enabled = true

        $activeReservations = 0;
        $monthlyRevenue     = 0;

        // ── Blocages actifs aujourd'hui ───────────────────────
        // blockable_type stocké en nom court 'room' OU complet selon le controller
        // On cherche les deux formats pour être sûr
        $blockedRoomIds = InventoryBlock::where('hostel_id', $hostelId)
            ->where(function ($q) {
                $q->where('blockable_type', \App\Models\Room::class)
                  ->orWhere('blockable_type', 'room');
            })
            ->where('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $today);
            })
            ->pluck('blockable_id')
            ->unique()
            ->values();

        // ── Chambres privées actives non bloquées ─────────────
        $privateRoomsCount = Room::where('hostel_id', $hostelId)
            ->where('type', 'private')
            ->where('is_enabled', true)
            ->whereNotIn('id', $blockedRoomIds)
            ->count();

        // ── Dortoirs actifs non bloqués ───────────────────────
        $dormitoryRoomsCount = Room::where('hostel_id', $hostelId)
            ->where('type', 'dormitory')
            ->where('is_enabled', true)
            ->whereNotIn('id', $blockedRoomIds)
            ->count();

        // ── Chambres indisponibles ─────────────────────────────
        $disabledRoomsCount    = Room::where('hostel_id', $hostelId)->where('is_enabled', false)->count();
        $unavailableRoomsCount = $disabledRoomsCount + $blockedRoomIds->count();

        // ── Tentes ────────────────────────────────────────────
        $activeTentSpacesCount   = TentSpace::where('hostel_id', $hostelId)->where('is_enabled', true)->count();
$inactiveTentSpacesCount = TentSpace::where('hostel_id', $hostelId)->where('is_enabled', false)->count();

        return view('dashboard', compact(
            'availableBeds',
            'activeReservations',
            'monthlyRevenue',
            'privateRoomsCount',
            'dormitoryRoomsCount',
            'unavailableRoomsCount',
            'blockedRoomIds',
            'activeTentSpacesCount',
            'inactiveTentSpacesCount'
        ));
    }
}