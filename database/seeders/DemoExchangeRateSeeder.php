<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * DemoExchangeRateSeeder — taux de change par hostel (EUR, USD, GBP).
 *
 * Logique métier :
 *   • Chaque hostel gère ses propres taux (isolation multi-tenant).
 *   • La méthode ExchangeRate::active() récupère le taux le plus récent (->latest()).
 *   • On simule 3 mises à jour historiques par devise (janv., mars, mai 2026)
 *     pour montrer l'évolution et permettre l'audit.
 *
 * Taux réalistes TND (mai 2026) :
 *   EUR : achat 3.3500 / vente 3.4200
 *   USD : achat 3.0600 / vente 3.1200
 *   GBP : achat 3.8600 / vente 3.9400
 *
 * Volume : 30 hostels × 3 devises × 3 entrées = 270 lignes.
 *
 * PRÉREQUIS : DemoHostelSeeder + DemoUserSeeder doivent avoir tourné.
 */
class DemoExchangeRateSeeder extends Seeder
{
    /**
     * Historique des taux par devise.
     * Format : [date_created, buy_rate, sell_rate]
     * Le dernier enregistrement est le taux actif (->latest()).
     */
    private const RATE_HISTORY = [
        'EUR' => [
            ['2026-01-15 08:00:00', 3.3100, 3.3800],
            ['2026-03-10 09:00:00', 3.3300, 3.4000],
            ['2026-05-05 08:30:00', 3.3500, 3.4200],
        ],
        'USD' => [
            ['2026-01-15 08:00:00', 2.9900, 3.0600],
            ['2026-03-10 09:00:00', 3.0200, 3.0900],
            ['2026-05-05 08:30:00', 3.0600, 3.1200],
        ],
        'GBP' => [
            ['2026-01-15 08:00:00', 3.8000, 3.8700],
            ['2026-03-10 09:00:00', 3.8300, 3.9100],
            ['2026-05-05 08:30:00', 3.8600, 3.9400],
        ],
    ];

    public function run(): void
    {
        $this->command->info('💱 [DemoExchangeRateSeeder] Taux de change...');

        // Premier financier par hostel (role = financial dans hostel_user)
        $financierByHostel = DB::table('hostel_user')
            ->where('role', 'financial')
            ->get()
            ->groupBy('hostel_id')
            ->map(fn($g) => $g->first()->user_id)
            ->toArray();

        $rows = [];

        for ($hostelId = 1; $hostelId <= 30; $hostelId++) {
            $createdBy = $financierByHostel[$hostelId] ?? null;

            foreach (self::RATE_HISTORY as $currency => $entries) {
                foreach ($entries as [$createdAt, $buyRate, $sellRate]) {
                    $rows[] = [
                        'hostel_id'       => $hostelId,
                        'currency'        => $currency,
                        'buy_rate_to_tnd' => $buyRate,
                        'sell_rate_to_tnd'=> $sellRate,
                        'created_by'      => $createdBy,
                        'created_at'      => $createdAt,
                        'updated_at'      => $createdAt,
                    ];
                }
            }
        }

        // Insertion par lots de 500 pour éviter les requêtes trop longues
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('exchange_rates')->insert($chunk);
        }

        $total    = DB::table('exchange_rates')->count();
        $devises  = implode(', ', array_keys(self::RATE_HISTORY));
        $history  = self::RATE_HISTORY;
        $entries  = count(reset($history));

        $this->command->line("   ↳ taux insérés : $total (30 hostels × 3 devises × $entries entrées)");
        $this->command->line("   ↳ devises      : $devises");
        $this->command->line("   ↳ taux actif   : entrée la plus récente par devise/hostel (->latest())");
        $this->command->info('✓ Taux de change OK.');
    }
}
