<?php

namespace App\Services\Analytics;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Service Analytics — encapsule toutes les agrégations SQL du dashboard.
 *
 * Architecture : un service par domaine (Acquisition, Occupancy, Revenue).
 * Chaque méthode publique retourne un array de KPIs + datasets prêts à
 * être consommés par les vues Blade et plus tard par Chart.js (Étape 3).
 *
 * Pattern PFE : Service Layer + DTO-like return arrays
 *               + Database query optimization (single GROUP BY queries)
 */
class AnalyticsService
{
    // ═══════════════════════════════════════════════════════════════════════
    // ONGLET 1 — ACQUISITION
    // Source des clients, nationalité, funnel de conversion
    // ═══════════════════════════════════════════════════════════════════════

    public function acquisitionData(int $hostelId): array
    {
        return [
            'kpis'         => $this->acquisitionKpis($hostelId),
            'by_source'    => $this->reservationsBySource($hostelId),
            'by_country'   => $this->peopleByCountry($hostelId),
            'rev_by_source'=> $this->revenueBySource($hostelId),
            'funnel'       => $this->contactRequestsFunnel($hostelId),
        ];
    }

    private function acquisitionKpis(int $hostelId): array
    {
        $totalClients = DB::table('reservation_people')
            ->join('reservations', 'reservation_people.reservation_id', '=', 'reservations.id')
            ->where('reservations.hostel_id', $hostelId)
            ->where('reservations.status', '!=', 'cancelled')
            ->count();

        $avgStay = DB::table('reservations')
            ->where('hostel_id', $hostelId)
            ->where('status', '!=', 'cancelled')
            ->avg('nights');

        // Taux de répétition : % de mainGuest ayant ≥ 2 réservations
        $guestStats = DB::table('reservations')
            ->where('hostel_id', $hostelId)
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('main_guest_id')
            ->select('main_guest_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('main_guest_id')
            ->get();

        $totalGuests   = $guestStats->count();
        $repeatGuests  = $guestStats->where('cnt', '>=', 2)->count();
        $repeatRate    = $totalGuests > 0 ? round($repeatGuests / $totalGuests * 100, 1) : 0;

        // Taux de conversion contact_requests (table peut ne pas exister)
        $conversionRate = 0;
        if ($this->tableExists('contact_requests')) {
            $totalRequests     = DB::table('contact_requests')
                ->where('hostel_id', $hostelId)
                ->count();
            $confirmedRequests = DB::table('contact_requests')
                ->where('hostel_id', $hostelId)
                ->where('status', 'confirmed')
                ->count();
            $conversionRate = $totalRequests > 0
                ? round($confirmedRequests / $totalRequests * 100, 1)
                : 0;
        }

        return [
            'total_clients'   => $totalClients,
            'avg_stay'        => round((float) $avgStay, 2),
            'repeat_rate'     => $repeatRate,
            'conversion_rate' => $conversionRate,
        ];
    }

    private function reservationsBySource(int $hostelId): array
    {
        $rows = DB::table('reservation_people')
            ->join('reservations', 'reservation_people.reservation_id', '=', 'reservations.id')
            ->where('reservations.hostel_id', $hostelId)
            ->where('reservations.status', '!=', 'cancelled')
            ->select(
                DB::raw('COALESCE(NULLIF(reservations.source, ""), "Non renseigné") as source'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('source')
            ->orderByDesc('count')
            ->get();

        $total = $rows->sum('count');

        return $rows->map(fn ($row) => [
            'source'  => $row->source,
            'count'   => (int) $row->count,
            'percent' => $total > 0 ? round($row->count / $total * 100, 2) : 0,
        ])->toArray();
    }

    private function peopleByCountry(int $hostelId): array
    {
        return DB::table('reservation_people')
            ->join('reservations', 'reservation_people.reservation_id', '=', 'reservations.id')
            ->where('reservations.hostel_id', $hostelId)
            ->where('reservations.status', '!=', 'cancelled')
            ->whereNotNull('reservation_people.nationality')
            ->select(
                'reservation_people.nationality as country',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('reservation_people.nationality')
            ->orderByDesc('total')
            ->limit(50)
            ->get()
            ->map(fn ($row) => [
                'country' => $row->country,
                'total'   => (int) $row->total,
            ])
            ->toArray();
    }

    private function revenueBySource(int $hostelId): array
    {
        return DB::table('reservations')
            ->where('hostel_id', $hostelId)
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('COALESCE(NULLIF(source, ""), "Non renseigné") as source'),
                DB::raw('SUM(total_price_tnd) as revenue'),
                DB::raw('COUNT(*) as reservations_count')
            )
            ->groupBy('source')
            ->orderByDesc('revenue')
            ->get()
            ->map(fn ($row) => [
                'source'             => $row->source,
                'revenue'            => round((float) $row->revenue, 3),
                'reservations_count' => (int) $row->reservations_count,
            ])
            ->toArray();
    }

    private function contactRequestsFunnel(int $hostelId): array
    {
        // Si la table n'existe pas, retourne un funnel vide propre
        if (!$this->tableExists('contact_requests')) {
            return ['total' => 0, 'stages' => []];
        }

        $statuses = ['new', 'read', 'replied', 'confirmed', 'cancelled'];

        $rows = DB::table('contact_requests')
            ->where('hostel_id', $hostelId)
            ->select('status', DB::raw('COUNT(*) as cnt'))
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        $total  = array_sum($rows);
        $funnel = [];

        foreach ($statuses as $status) {
            $count = $rows[$status] ?? 0;
            $funnel[] = [
                'status'  => $status,
                'label'   => $this->statusLabel($status),
                'count'   => $count,
                'percent' => $total > 0 ? round($count / $total * 100, 1) : 0,
            ];
        }

        return [
            'total'  => $total,
            'stages' => $funnel,
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ONGLET 2 — OCCUPATION
    // ═══════════════════════════════════════════════════════════════════════

    public function occupancyData(int $hostelId): array
    {
        return [
            'kpis'           => $this->occupancyKpis($hostelId),
            'monthly_trend'  => $this->occupancyMonthlyTrend($hostelId),
            'by_unit_type'   => $this->occupancyByUnitType($hostelId),
            'status_split'   => $this->reservationsStatusSplit($hostelId),
        ];
    }

    private function occupancyKpis(int $hostelId): array
    {
        $leadTime = DB::table('reservations')
            ->where('hostel_id', $hostelId)
            ->where('status', '!=', 'cancelled')
            ->select(DB::raw('AVG(DATEDIFF(start_date, created_at)) as avg_lead'))
            ->value('avg_lead');

        $avgNights = DB::table('reservations')
            ->where('hostel_id', $hostelId)
            ->where('status', '!=', 'cancelled')
            ->avg('nights');

        $total = DB::table('reservations')->where('hostel_id', $hostelId)->count();
        $cancelled = DB::table('reservations')
            ->where('hostel_id', $hostelId)
            ->where('status', 'cancelled')
            ->count();
        $cancelRate = $total > 0 ? round($cancelled / $total * 100, 1) : 0;

        // Taux d'occupation : nuits-personnes vendues / (capacité totale × 365)
        $totalPersonNights = DB::table('reservation_people')
            ->join('reservations', 'reservation_people.reservation_id', '=', 'reservations.id')
            ->where('reservations.hostel_id', $hostelId)
            ->where('reservations.status', '!=', 'cancelled')
            ->sum(DB::raw('reservations.nights'));

        $capacity        = $this->hostelCapacity($hostelId);
        $daysAnalyzed    = 365;
        $maxPersonNights = $capacity * $daysAnalyzed;
        $occupancyRate   = $maxPersonNights > 0
            ? round($totalPersonNights / $maxPersonNights * 100, 1)
            : 0;

        return [
            'occupancy_rate' => $occupancyRate,
            'lead_time'      => round((float) $leadTime, 1),
            'avg_nights'     => round((float) $avgNights, 2),
            'cancel_rate'    => $cancelRate,
        ];
    }

    private function occupancyMonthlyTrend(int $hostelId): array
    {
        return DB::table('reservation_people')
            ->join('reservations', 'reservation_people.reservation_id', '=', 'reservations.id')
            ->where('reservations.hostel_id', $hostelId)
            ->where('reservations.status', '!=', 'cancelled')
            ->select(
                DB::raw('DATE_FORMAT(reservations.start_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as person_count'),
                DB::raw('SUM(reservations.nights) as person_nights')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn ($row) => [
                'month'         => $row->month,
                'person_count'  => (int) $row->person_count,
                'person_nights' => (int) $row->person_nights,
            ])
            ->toArray();
    }

    private function occupancyByUnitType(int $hostelId): array
    {
        return DB::table('reservation_people')
            ->join('reservations', 'reservation_people.reservation_id', '=', 'reservations.id')
            ->where('reservations.hostel_id', $hostelId)
            ->where('reservations.status', '!=', 'cancelled')
            ->select(
                'reservation_people.item_type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(reservation_people.price_tnd) as revenue'),
                DB::raw('AVG(reservation_people.price_tnd) as avg_price')
            )
            ->groupBy('reservation_people.item_type')
            ->orderByDesc('count')
            ->get()
            ->map(fn ($row) => [
                'item_type' => $row->item_type,
                'label'     => $this->itemTypeLabel($row->item_type),
                'count'     => (int) $row->count,
                'revenue'   => round((float) $row->revenue, 3),
                'avg_price' => round((float) $row->avg_price, 3),
            ])
            ->toArray();
    }

    private function reservationsStatusSplit(int $hostelId): array
    {
        return DB::table('reservations')
            ->where('hostel_id', $hostelId)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->orderByDesc('count')
            ->get()
            ->map(fn ($row) => [
                'status' => $row->status,
                'label'  => $this->statusLabel($row->status),
                'count'  => (int) $row->count,
            ])
            ->toArray();
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ONGLET 3 — REVENUE (data en backup, mais le tab Finance utilise son
    // propre dataset depuis AnalyticsController::compute())
    // ═══════════════════════════════════════════════════════════════════════

    public function revenueData(int $hostelId): array
    {
        return [
            'kpis'             => $this->revenueKpis($hostelId),
            'monthly_revenue'  => $this->monthlyRevenue($hostelId),
            'by_currency'      => $this->revenueByCurrency($hostelId),
        ];
    }

    private function revenueKpis(int $hostelId): array
    {
        $totalRevenue = DB::table('reservations')
            ->where('hostel_id', $hostelId)
            ->where('status', '!=', 'cancelled')
            ->sum('total_price_tnd');

        $totalPersonNights = DB::table('reservation_people')
            ->join('reservations', 'reservation_people.reservation_id', '=', 'reservations.id')
            ->where('reservations.hostel_id', $hostelId)
            ->where('reservations.status', '!=', 'cancelled')
            ->sum(DB::raw('reservations.nights'));

        $adr = $totalPersonNights > 0
            ? round($totalRevenue / $totalPersonNights, 3)
            : 0;

        $capacity = $this->hostelCapacity($hostelId);
        $days = 365;
        $revpar = ($capacity * $days) > 0
            ? round($totalRevenue / ($capacity * $days), 3)
            : 0;

        return [
            'total_revenue' => round((float) $totalRevenue, 3),
            'adr'           => $adr,
            'revpar'        => $revpar,
        ];
    }

    private function monthlyRevenue(int $hostelId): array
    {
        return DB::table('reservations')
            ->where('hostel_id', $hostelId)
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('DATE_FORMAT(start_date, "%Y-%m") as month'),
                DB::raw('SUM(total_price_tnd) as revenue'),
                DB::raw('COUNT(*) as reservations_count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn ($row) => [
                'month'              => $row->month,
                'revenue'            => round((float) $row->revenue, 3),
                'reservations_count' => (int) $row->reservations_count,
            ])
            ->toArray();
    }

    private function revenueByCurrency(int $hostelId): array
    {
        return DB::table('reservation_people')
            ->join('reservations', 'reservation_people.reservation_id', '=', 'reservations.id')
            ->where('reservations.hostel_id', $hostelId)
            ->where('reservations.status', '!=', 'cancelled')
            ->select(
                'reservation_people.currency',
                DB::raw('SUM(reservation_people.price_tnd) as revenue_tnd'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('reservation_people.currency')
            ->orderByDesc('revenue_tnd')
            ->get()
            ->map(fn ($row) => [
                'currency'    => $row->currency,
                'revenue_tnd' => round((float) $row->revenue_tnd, 3),
                'count'       => (int) $row->count,
            ])
            ->toArray();
    }

    // ═══════════════════════════════════════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Capacité totale du hostel.
     *
     * Schéma HostelFlow réel (confirmé via DESCRIBE) :
     *  - beds         : 1 lit = 1 personne (compte les lignes)
     *  - rooms        : colonne `max_capacity` (chambres privées)
     *  - tent_spaces  : colonne `max_persons` (capacité réelle en personnes,
     *                   pas `max_tents` qui est juste le nombre de tentes)
     */
    private function hostelCapacity(int $hostelId): int
    {
        // 1. Lits en dortoir
        $beds = DB::table('beds')
            ->join('rooms', 'beds.room_id', '=', 'rooms.id')
            ->where('rooms.hostel_id', $hostelId)
            ->where('rooms.is_enabled', true)
            ->where('beds.is_enabled', true)
            ->count();

        // 2. Capacité des chambres privées
        $privateCapacity = (int) DB::table('rooms')
            ->where('hostel_id', $hostelId)
            ->where('type', 'private')
            ->where('is_enabled', true)
            ->sum('max_capacity');

        // 3. Capacité des espaces tentes (en personnes, pas en tentes)
        $tentCapacity = (int) DB::table('tent_spaces')
            ->where('hostel_id', $hostelId)
            ->where('is_enabled', true)
            ->sum('max_persons');

        return $beds + $privateCapacity + $tentCapacity;
    }

    private function tableExists(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'new'         => '🆕 Nouvelles demandes',
            'read'        => '👁 Lues',
            'replied'     => '💬 Répondues',
            'confirmed'   => '✅ Confirmées',
            'cancelled'   => '❌ Annulées',
            'pending'     => '⏳ En attente',
            'checked_in'  => '🔑 Check-in',
            'checked_out' => '👋 Check-out',
            default       => ucfirst($status),
        };
    }

    private function itemTypeLabel(string $type): string
    {
        return match ($type) {
            'bed'        => '🛏 Dormitory',
            'room'       => '🚪 Chambre privée',
            'tent_space' => '⛺ Tente',
            default      => ucfirst($type),
        };
    }
}