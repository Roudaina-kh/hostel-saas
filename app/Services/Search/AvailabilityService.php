<?php

namespace App\Services\Search;

use Illuminate\Support\Facades\DB;

class AvailabilityService
{
    /**
     * Filtre les hostel_ids qui ont au moins $guests places disponibles.
     * Capacité = SUM(rooms.max_capacity) + SUM(tent_spaces.max_persons)
     */
    public function filterAvailableHostels(
        array  $hostelIds,
        string $checkIn,
        string $checkOut,
        int    $guests = 1
    ): array {
        if (empty($hostelIds)) return [];

        // Capacité chambres
        $roomsCapacity = DB::table('rooms')
            ->whereIn('hostel_id', $hostelIds)
            ->where('is_enabled', true)
            ->select('hostel_id', DB::raw('SUM(max_capacity) as capacity'))
            ->groupBy('hostel_id')
            ->pluck('capacity', 'hostel_id')
            ->toArray();

        // Capacité tentes
        $tentsCapacity = DB::table('tent_spaces')
            ->whereIn('hostel_id', $hostelIds)
            ->where('is_enabled', true)
            ->select('hostel_id', DB::raw('SUM(max_persons) as capacity'))
            ->groupBy('hostel_id')
            ->pluck('capacity', 'hostel_id')
            ->toArray();

        // Occupation actuelle (toutes catégories d'item confondues)
        $occupiedByHostel = DB::table('reservation_people as rp')
            ->join('reservations as r', 'r.id', '=', 'rp.reservation_id')
            ->whereIn('r.hostel_id', $hostelIds)
            ->whereNotIn('r.status', ['cancelled'])
            ->where('r.start_date', '<', $checkOut)
            ->where('r.end_date',   '>', $checkIn)
            ->select('r.hostel_id', DB::raw('COUNT(*) as occupied'))
            ->groupBy('r.hostel_id')
            ->pluck('occupied', 'hostel_id')
            ->toArray();

        return array_values(array_filter($hostelIds, function ($id) use (
            $roomsCapacity, $tentsCapacity, $occupiedByHostel, $guests
        ) {
            $capacity = (int) (($roomsCapacity[$id] ?? 0) + ($tentsCapacity[$id] ?? 0));
            $occupied = (int) ($occupiedByHostel[$id] ?? 0);
            return ($capacity - $occupied) >= $guests;
        }));
    }

    /**
     * Disponibilité détaillée pour 1 hostel.
     */
    public function getHostelAvailability(int $hostelId, string $checkIn, string $checkOut): array
    {
        // Capacité chambres
        $roomsCapacity = (int) DB::table('rooms')
            ->where('hostel_id', $hostelId)
            ->where('is_enabled', true)
            ->sum('max_capacity');

        // Capacité tentes
        $tentsCapacity = (int) DB::table('tent_spaces')
            ->where('hostel_id', $hostelId)
            ->where('is_enabled', true)
            ->sum('max_persons');

        $totalCapacity = $roomsCapacity + $tentsCapacity;

        // Occupation
        $occupied = (int) DB::table('reservation_people as rp')
            ->join('reservations as r', 'r.id', '=', 'rp.reservation_id')
            ->where('r.hostel_id', $hostelId)
            ->whereNotIn('r.status', ['cancelled'])
            ->where('r.start_date', '<', $checkOut)
            ->where('r.end_date',   '>', $checkIn)
            ->count();

        $available = max(0, $totalCapacity - $occupied);

        return [
            'available'  => $available,
            'total'      => $totalCapacity,
            'occupied'   => $occupied,
            'percentage' => $totalCapacity > 0 ? round($available / $totalCapacity * 100) : 0,
            'status'     => match (true) {
                $totalCapacity === 0 => 'full',   // hostel pas encore configuré
                $available === 0     => 'full',
                $available <= 3      => 'low',
                default              => 'available',
            },
        ];
    }
}