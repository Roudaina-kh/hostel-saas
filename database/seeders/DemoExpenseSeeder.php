<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * DemoExpenseSeeder — dépenses opérationnelles réalistes pour les 30 hostels.
 *
 * Chaque hostel reçoit ~37 dépenses couvrant 12 mois (Jun 2025 – May 2026) :
 *   • 12 × charges mensuelles (STEG + SONEDE)  — saisonnalité : été ↑, hiver ↑
 *   • 12 × salaires mensuels                   — bonus juillet + décembre
 *   • 4  × maintenance trimestrielle
 *   • 4  × nettoyage trimestriel
 *   • 3  × fournitures (supplies)
 *   • 2  × campagnes marketing
 *
 * Variation par hostel : chaque hostel reçoit un coefficient VARIANCE unique
 * (0.82 – 1.28), ce qui rend chaque dashboard financier distinct.
 * Complexes de Jeunes × 1.5 (établissements plus grands).
 *
 * PRÉREQUIS : DemoHostelSeeder + DemoUserSeeder doivent avoir tourné.
 */
class DemoExpenseSeeder extends Seeder
{
    /** Complexes de Jeunes (plus grands → coefficient ×1.5) */
    private const COMPLEXE_IDS = [1, 2, 3, 4, 6, 8, 10, 11, 12, 15, 16, 19, 21, 24, 25];

    /**
     * Coefficient propre à chaque hostel (0.82 – 1.28).
     * Résultat : chaque hostel a un profil financier unique.
     */
    private const VARIANCE = [
         1 => 1.00,  2 => 1.18,  3 => 0.88,  4 => 1.25,  5 => 0.92,
         6 => 1.12,  7 => 0.95,  8 => 1.22,  9 => 0.87, 10 => 1.15,
        11 => 1.03, 12 => 0.91, 13 => 1.20, 14 => 1.07, 15 => 0.93,
        16 => 1.17, 17 => 0.89, 18 => 1.24, 19 => 1.01, 20 => 1.13,
        21 => 0.94, 22 => 1.19, 23 => 1.06, 24 => 0.90, 25 => 1.21,
        26 => 1.04, 27 => 0.97, 28 => 1.16, 29 => 1.08, 30 => 0.86,
    ];

    /**
     * Facteur saisonnier des charges (électricité/eau).
     * Clim en été ↑, chauffage en hiver ↑.
     */
    private const UTILITY_SEASON = [
        '2025-06' => 1.35, '2025-07' => 1.55, '2025-08' => 1.50,
        '2025-09' => 1.15, '2025-10' => 1.00, '2025-11' => 1.05,
        '2025-12' => 1.20, '2026-01' => 1.18, '2026-02' => 1.08,
        '2026-03' => 1.00, '2026-04' => 1.05, '2026-05' => 1.12,
    ];

    // ─────────────────────────────────────────────────────────────────────────

    public function run(): void
    {
        $this->command->info('📋 [DemoExpenseSeeder] Création des dépenses opérationnelles...');
        $now = now();

        $hostels = DB::table('hostels')->select('id', 'owner_id', 'name')->get()->keyBy('id');

        $managerByHostel = DB::table('hostel_user')
            ->where('role', 'manager')->get()
            ->groupBy('hostel_id')
            ->map(fn($g) => $g->first()->user_id);

        for ($hostelId = 1; $hostelId <= 30; $hostelId++) {
            $hostel = $hostels[$hostelId] ?? null;
            if (!$hostel) continue;

            $isLarge = in_array($hostelId, self::COMPLEXE_IDS);
            $f       = $isLarge ? 1.5 : 1.0;
            $v       = self::VARIANCE[$hostelId] ?? 1.0;
            $dayBase = 25 + ($hostelId % 4); // jour de paiement : 25-28 du mois

            $this->insertExpenses(
                $hostelId,
                $hostel->owner_id,
                $managerByHostel[$hostelId] ?? null,
                $isLarge ? 'Manager (Complexe)' : 'Manager (Maison de Jeunes)',
                $f, $v, $dayBase, $now
            );
        }

        $total = DB::table('expenses')->count();
        $this->command->line("   ↳ dépenses total : $total (~" . round($total / 30) . '/hostel)');
        $this->command->info('✓ Dépenses OK.');
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function insertExpenses(
        int    $hostelId,
        int    $ownerId,
        ?int   $userId,
        string $creatorLabel,
        float  $f,
        float  $v,
        int    $dayBase,
        $now
    ): void {
        $rows = [];

        /* ── 1. Charges mensuelles (utilities) + Salaires — Jun 2025 à May 2026 ── */
        foreach (self::UTILITY_SEASON as $ym => $seasonFactor) {
            [$year, $month] = explode('-', $ym);
            $day = str_pad(min($dayBase, (int) date('t', mktime(0, 0, 0, $month, 1, $year))), 2, '0', STR_PAD_LEFT);
            $expDate = "$ym-$day";

            // Charges (STEG + SONEDE) — saisonnières
            $rows[] = $this->row($hostelId, $ownerId, $userId, $creatorLabel, $now, [
                'category'     => 'utilities',
                'label'        => "Charges STEG + SONEDE — {$this->monthLabel($ym)}",
                'amount'       => round(300 * $f * $v * $seasonFactor, 3),
                'payer_name'   => 'Gestionnaire administratif',
                'expense_date' => $expDate,
                'note'         => null,
            ]);

            // Salaires — bonus juillet et décembre
            $bonusFactor = in_array($ym, ['2025-07', '2025-12']) ? 1.10 : 1.0;
            $rows[] = $this->row($hostelId, $ownerId, $userId, $creatorLabel, $now, [
                'category'     => 'salary',
                'label'        => "Salaires personnel — {$this->monthLabel($ym)}",
                'amount'       => round(2500 * $f * $v * $bonusFactor, 3),
                'payer_name'   => 'Direction des ressources humaines',
                'expense_date' => $expDate,
                'note'         => in_array($ym, ['2025-07', '2025-12']) ? 'Prime mensuelle incluse.' : null,
            ]);
        }

        /* ── 2. Maintenance trimestrielle ─────────────────────────────────────── */
        $maintenanceDates = [
            ['2025-08-' . str_pad(10 + ($hostelId % 5), 2, '0', STR_PAD_LEFT), 'Révision système électrique et climatisation'],
            ['2025-11-' . str_pad(8  + ($hostelId % 6), 2, '0', STR_PAD_LEFT), 'Entretien préventif chauffe-eau et plomberie'],
            ['2026-02-' . str_pad(12 + ($hostelId % 4), 2, '0', STR_PAD_LEFT), 'Réparation réseau plomberie dortoir'],
            ['2026-05-' . str_pad(5  + ($hostelId % 7), 2, '0', STR_PAD_LEFT), 'Mise à jour équipements et peinture couloir'],
        ];
        foreach ($maintenanceDates as [$date, $label]) {
            $rows[] = $this->row($hostelId, $ownerId, $userId, $creatorLabel, $now, [
                'category'     => 'maintenance',
                'label'        => $label,
                'amount'       => round(185 * $f * $v, 3),
                'payer_name'   => 'Artisan local agréé',
                'expense_date' => $date,
                'note'         => null,
            ]);
        }

        /* ── 3. Nettoyage professionnel trimestriel ──────────────────────────── */
        $cleaningDates = [
            ['2025-07-' . str_pad(14 + ($hostelId % 5), 2, '0', STR_PAD_LEFT), 'Nettoyage de fond + désinfection — Été 2025'],
            ['2025-10-' . str_pad(18 + ($hostelId % 4), 2, '0', STR_PAD_LEFT), 'Désinfection complète — Automne 2025'],
            ['2026-01-' . str_pad(12 + ($hostelId % 6), 2, '0', STR_PAD_LEFT), 'Nettoyage de fond — Hiver 2026'],
            ['2026-04-' . str_pad(20 + ($hostelId % 5), 2, '0', STR_PAD_LEFT), 'Désinfection profonde — Printemps 2026'],
        ];
        foreach ($cleaningDates as [$date, $label]) {
            $rows[] = $this->row($hostelId, $ownerId, $userId, $creatorLabel, $now, [
                'category'     => 'cleaning',
                'label'        => $label,
                'amount'       => round(220 * $f * $v, 3),
                'payer_name'   => 'Prestataire nettoyage contractuel',
                'expense_date' => $date,
                'note'         => null,
            ]);
        }

        /* ── 4. Fournitures (supplies) ────────────────────────────────────────── */
        $suppliesDates = [
            ['2025-09-' . str_pad(3  + ($hostelId % 5), 2, '0', STR_PAD_LEFT), 'Renouvellement linge de maison et équipements'],
            ['2025-12-' . str_pad(15 + ($hostelId % 4), 2, '0', STR_PAD_LEFT), 'Achat matériel hivernal et articles ménagers'],
            ['2026-03-' . str_pad(10 + ($hostelId % 6), 2, '0', STR_PAD_LEFT), 'Fournitures nettoyage, literie et produits'],
        ];
        foreach ($suppliesDates as [$date, $label]) {
            $rows[] = $this->row($hostelId, $ownerId, $userId, $creatorLabel, $now, [
                'category'     => 'supplies',
                'label'        => $label,
                'amount'       => round(310 * $f * $v, 3),
                'payer_name'   => 'Responsable approvisionnement',
                'expense_date' => $date,
                'note'         => null,
            ]);
        }

        /* ── 5. Marketing digital (2 campagnes / an) ─────────────────────────── */
        $marketingDates = [
            ['2025-06-0' . (1 + $hostelId % 5), 'Campagne promotion digitale — Saison estivale 2025'],
            ['2026-01-' . str_pad(15 + ($hostelId % 4), 2, '0', STR_PAD_LEFT), 'Campagne promotion digitale — Printemps 2026'],
        ];
        foreach ($marketingDates as [$date, $label]) {
            $rows[] = $this->row($hostelId, $ownerId, $userId, $creatorLabel, $now, [
                'category'     => 'marketing',
                'label'        => $label,
                'amount'       => round(120 * $f * $v, 3),
                'payer_name'   => 'Agence communication numérique',
                'expense_date' => $date,
                'note'         => null,
            ]);
        }

        DB::table('expenses')->insert($rows);
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function row(int $hostelId, int $ownerId, ?int $userId, string $creatorLabel, $now, array $e): array
    {
        return [
            'hostel_id'     => $hostelId,
            'user_id'       => $userId,
            'owner_id'      => $ownerId,
            'creator_label' => $creatorLabel,
            'payer_name'    => $e['payer_name'],
            'category'      => $e['category'],
            'label'         => $e['label'],
            'amount'        => $e['amount'],
            'currency'      => 'TND',
            'expense_date'  => $e['expense_date'],
            'note'          => $e['note'],
            'created_at'    => $now,
            'updated_at'    => $now,
        ];
    }

    private function monthLabel(string $ym): string
    {
        $months = [
            '01' => 'Janvier', '02' => 'Février', '03' => 'Mars',
            '04' => 'Avril',   '05' => 'Mai',      '06' => 'Juin',
            '07' => 'Juillet', '08' => 'Août',     '09' => 'Septembre',
            '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre',
        ];
        [$year, $month] = explode('-', $ym);
        return ($months[$month] ?? $month) . ' ' . $year;
    }
}
