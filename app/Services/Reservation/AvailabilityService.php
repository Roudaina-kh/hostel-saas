<?php

namespace App\Services\Reservation;

use App\Models\Bed;
use App\Models\Room;
use App\Models\TentSpace;
use Illuminate\Support\Facades\DB;

/**
 * FIX 1 — Dortoir : 1 lit = 1 personne max
 * - Vérification que le lit appartient bien au hostel (guard hostel)
 * - Vérification stricte par bed_id (pas par capacity)
 * - Guard: le lit doit être enabled ET appartenir à une room enabled du bon hostel
 */
class AvailabilityService
{
    public function getAvailableUnits(int $hostelId, string $startDate, string $endDate): array
    {
        $rooms = Room::with(['beds' => fn ($q) => $q->where('is_enabled', true)])
            ->where('hostel_id', $hostelId)
            ->where('is_enabled', true)
            ->get();

        $beds         = [];
        $privateRooms = [];

        foreach ($rooms as $room) {

            if ($room->type === 'dormitory') {
                // ── FIX 1 : chaque lit = 1 personne, vérification stricte ──
                foreach ($room->beds as $bed) {
                    // isAvailable vérifie qu'aucune reservation_person n'utilise ce bed_id
                    if ($this->isAvailable($hostelId, 'bed', $bed->id, $startDate, $endDate)) {
                        $beds[] = [
                            'id'        => $bed->id,
                            'name'      => $room->name . ' — ' . $bed->name,
                            'room_id'   => $room->id,
                            'room_name' => $room->name,
                            // Pas de remaining_capacity pour les lits : 1 lit = 1 place
                        ];
                    }
                }
            }

            if ($room->type === 'private') {
                $capacity  = (int) ($room->capacity ?? 1);
                $remaining = $this->remainingCapacity(
                    $hostelId, 'room', $room->id, $capacity, $startDate, $endDate
                );
                if ($remaining > 0) {
                    $privateRooms[] = [
                        'id'                 => $room->id,
                        'name'               => $room->name,
                        'capacity'           => $capacity,
                        'remaining_capacity' => $remaining,
                    ];
                }
            }
        }

        $tentSpaces = TentSpace::where('hostel_id', $hostelId)
            ->where('is_enabled', true)
            ->get()
            ->map(function ($space) use ($hostelId, $startDate, $endDate) {
                $capacity  = (int) ($space->capacity ?? 1);
                $remaining = $this->remainingCapacity(
                    $hostelId, 'tent_space', $space->id, $capacity, $startDate, $endDate
                );
                if ($remaining <= 0) return null;
                return [
                    'id'                 => $space->id,
                    'name'               => $space->name,
                    'capacity'           => $capacity,
                    'remaining_capacity' => $remaining,
                ];
            })
            ->filter()
            ->values();

        return [
            'beds'        => $beds,
            'rooms'       => $privateRooms,
            'tent_spaces' => $tentSpaces,
        ];
    }

    /**
     * Vérifie si une unité spécifique est disponible.
     *
     * Pour 'bed' : vérifie qu'aucune reservation_person n'occupe ce bed_id
     *              sur la même période (1 lit = 1 personne max).
     */
    public function isAvailable(
        int    $hostelId,
        string $itemType,
        int    $itemId,
        string $startDate,
        string $endDate,
        ?int   $ignoreReservationId = null
    ): bool {
        if ($this->isBlocked($hostelId, $itemType, $itemId, $startDate, $endDate)) {
            return false;
        }

        return match ($itemType) {

            // ── FIX 1 : bed = 1 personne strictement ──────────────────────
            'bed' => (function () use ($hostelId, $itemId, $startDate, $endDate, $ignoreReservationId) {
                // Vérifier que le lit appartient bien à ce hostel (guard sécurité)
                $bed = Bed::whereHas(
                    'room',
                    fn ($q) => $q->where('hostel_id', $hostelId)->where('is_enabled', true)
                )->where('is_enabled', true)->find($itemId);

                if (!$bed) return false; // Lit inexistant ou n'appartient pas au hostel

                // 1 lit = 1 personne : pas de conflit sur ce bed_id
                return !$this->hasConflict($hostelId, 'bed', $itemId, $startDate, $endDate, $ignoreReservationId);
            })(),

            'room' => (function () use ($hostelId, $itemId, $startDate, $endDate, $ignoreReservationId) {
                $room = Room::where('hostel_id', $hostelId)->find($itemId);
                if (!$room) return false;
                $capacity = (int) ($room->capacity ?? 1);
                return $this->remainingCapacity(
                    $hostelId, 'room', $itemId, $capacity, $startDate, $endDate, $ignoreReservationId
                ) > 0;
            })(),

            'tent_space' => (function () use ($hostelId, $itemId, $startDate, $endDate, $ignoreReservationId) {
                $space = TentSpace::where('hostel_id', $hostelId)->find($itemId);
                if (!$space) return false;
                $capacity = (int) ($space->capacity ?? 1);
                return $this->remainingCapacity(
                    $hostelId, 'tent_space', $itemId, $capacity, $startDate, $endDate, $ignoreReservationId
                ) > 0;
            })(),

            default => false,
        };
    }

    // ─── Privé ──────────────────────────────────────────────────────────────

    private function remainingCapacity(
        int    $hostelId,
        string $itemType,
        int    $itemId,
        int    $capacity,
        string $startDate,
        string $endDate,
        ?int   $ignoreReservationId = null
    ): int {
        if ($this->isBlocked($hostelId, $itemType, $itemId, $startDate, $endDate)) {
            return 0;
        }

        $query = DB::table('reservation_people')
            ->join('reservations', 'reservations.id', '=', 'reservation_people.reservation_id')
            ->where('reservations.hostel_id', $hostelId)
            ->whereNotIn('reservations.status', ['cancelled'])
            ->where('reservation_people.item_type', $itemType)
            ->where('reservation_people.item_id', $itemId)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where('reservations.start_date', '<', $endDate)
                  ->where('reservations.end_date', '>', $startDate);
            });

        if ($ignoreReservationId) {
            $query->where('reservations.id', '!=', $ignoreReservationId);
        }

        $reserved = $query->count();
        return max(0, $capacity - $reserved);
    }

    private function hasConflict(
        int    $hostelId,
        string $itemType,
        int    $itemId,
        string $startDate,
        string $endDate,
        ?int   $ignoreReservationId = null
    ): bool {
        $query = DB::table('reservation_people')
            ->join('reservations', 'reservations.id', '=', 'reservation_people.reservation_id')
            ->where('reservations.hostel_id', $hostelId)
            ->whereNotIn('reservations.status', ['cancelled'])
            ->where('reservation_people.item_type', $itemType)
            ->where('reservation_people.item_id', $itemId)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where('reservations.start_date', '<', $endDate)
                  ->where('reservations.end_date', '>', $startDate);
            });

        if ($ignoreReservationId) {
            $query->where('reservations.id', '!=', $ignoreReservationId);
        }

        return $query->exists();
    }

    private function isBlocked(
        int    $hostelId,
        string $itemType,
        int    $itemId,
        string $startDate,
        string $endDate
    ): bool {
        $classMap = [
            'bed'        => \App\Models\Bed::class,
            'room'       => \App\Models\Room::class,
            'tent_space' => \App\Models\TentSpace::class,
        ];

        return DB::table('inventory_blocks')
            ->where('hostel_id', $hostelId)
            ->whereIn('blockable_type', [$itemType, $classMap[$itemType] ?? $itemType])
            ->where('blockable_id', $itemId)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where('start_date', '<', $endDate)
                  ->where('end_date', '>', $startDate);
            })
            ->exists();
    }
}