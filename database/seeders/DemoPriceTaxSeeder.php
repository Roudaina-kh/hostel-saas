<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * DemoPriceTaxSeeder — tarifs, taxes, pivot price_tax, blocs d'inventaire.
 *
 * Par hostel :
 *   • 2 taxes : TVA 7 % + Taxe de séjour 1 TND/pers/nuit
 *   • 1 prix par chambre (per_room ou per_bed selon le type)
 *   • 1 prix par espace tente (per_person)
 *   • Chaque prix est lié aux 2 taxes via le pivot price_tax
 *
 * Blocs d'inventaire :
 *   • 10 blocs (1 par 3 hostels) — rotation maintenance / lit / tente
 *
 * Grille tarifaire HT (TND/nuit) :
 *   Chambre Simple : 30  | Chambre Double : 55  | Chambre Triple : 75
 *   Dortoir 4 lits : 15  | Dortoir 6 lits : 13  | Dortoir 8 lits : 12
 *   Dortoir 10 lits: 10  | Espace Tentes A: 12  | Espace Tentes B: 10
 *
 * PRÉREQUIS : DemoRoomSeeder doit avoir tourné.
 */
class DemoPriceTaxSeeder extends Seeder
{
    private const TVA         = 0.07;
    private const VALID_FROM  = '2026-01-01';

    private const PRICE_HT = [
        'Chambre Simple 1' => 30.0,
        'Chambre Simple 2' => 30.0,
        'Chambre Double 1' => 55.0,
        'Chambre Double 2' => 55.0,
        'Chambre Triple 1' => 75.0,
        'Dortoir 4 lits'   => 15.0,
        'Dortoir 6 lits'   => 13.0,
        'Dortoir 8 lits'   => 12.0,
        'Dortoir 10 lits'  => 10.0,
        'Espace Tentes A'  => 12.0,
        'Espace Tentes B'  => 10.0,
    ];

    /** Hostels qui reçoivent un bloc inventaire (1 sur 3) */
    private const BLOCK_HOSTELS = [1, 4, 7, 10, 13, 16, 19, 22, 25, 28];

    // ─────────────────────────────────────────────────────────────────────────

    public function run(): void
    {
        $this->command->info('💰 [DemoPriceTaxSeeder] Tarifs, taxes et blocs inventaire...');
        $now = now();

        for ($hostelId = 1; $hostelId <= 30; $hostelId++) {
            [$tvaId, $sejourId] = $this->insertTaxes($hostelId, $now);
            $this->insertPrices($hostelId, [$tvaId, $sejourId], $now);
        }

        $this->insertInventoryBlocks($now);

        $this->command->line('   ↳ taxes            : ' . DB::table('taxes')->count() . ' (30 hostels × 2)');
        $this->command->line('   ↳ prix             : ' . DB::table('prices')->count());
        $this->command->line('   ↳ price_tax        : ' . DB::table('price_tax')->count());
        $this->command->line('   ↳ blocs inventaire : ' . DB::table('inventory_blocks')->count() . ' (1/3 hostels)');
        $this->command->info('✓ Prix + Taxes + Blocs OK.');
    }

    /* ── Taxes ────────────────────────────────────────────────────────────── */

    private function insertTaxes(int $hostelId, $now): array
    {
        $tvaId = DB::table('taxes')->insertGetId([
            'hostel_id'   => $hostelId,
            'name'        => 'TVA Hébergement',
            'type'        => 'percentage',
            'amount'      => 7.000,
            'is_enabled'  => true,
            'description' => 'Taxe sur la valeur ajoutée appliquée à l\'hébergement (7 %).',
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        $sejourId = DB::table('taxes')->insertGetId([
            'hostel_id'   => $hostelId,
            'name'        => 'Taxe de séjour',
            'type'        => 'fixed_per_person_per_night',
            'amount'      => 1.000,
            'is_enabled'  => true,
            'description' => 'Taxe de séjour touristique : 1 TND par personne et par nuit.',
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        return [$tvaId, $sejourId];
    }

    /* ── Prix (rooms + tent_spaces) ───────────────────────────────────────── */

    private function insertPrices(int $hostelId, array $taxIds, $now): void
    {
        $rooms = DB::table('rooms')->where('hostel_id', $hostelId)->get();

        foreach ($rooms as $room) {
            $ht   = self::PRICE_HT[$room->name] ?? 20.0;
            $ttc  = round($ht * (1 + self::TVA), 3);
            $mode = $room->type === 'private' ? 'per_room' : 'per_bed';

            $priceId = DB::table('prices')->insertGetId([
                'hostel_id'      => $hostelId,
                'priceable_type' => 'App\Models\Room',
                'priceable_id'   => $room->id,
                'pricing_mode'   => $mode,
                'price_ht'       => $ht,
                'price_ttc'      => $ttc,
                'valid_from'     => self::VALID_FROM,
                'valid_to'       => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

            $this->linkTaxes($priceId, $taxIds, $now);
        }

        $tentSpaces = DB::table('tent_spaces')->where('hostel_id', $hostelId)->get();

        foreach ($tentSpaces as $ts) {
            $ht  = self::PRICE_HT[$ts->name] ?? 10.0;
            $ttc = round($ht * (1 + self::TVA), 3);

            $priceId = DB::table('prices')->insertGetId([
                'hostel_id'      => $hostelId,
                'priceable_type' => 'App\Models\TentSpace',
                'priceable_id'   => $ts->id,
                'pricing_mode'   => 'per_person',
                'price_ht'       => $ht,
                'price_ttc'      => $ttc,
                'valid_from'     => self::VALID_FROM,
                'valid_to'       => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

            $this->linkTaxes($priceId, $taxIds, $now);
        }
    }

    private function linkTaxes(int $priceId, array $taxIds, $now): void
    {
        $rows = [];
        foreach ($taxIds as $taxId) {
            $rows[] = [
                'price_id'   => $priceId,
                'tax_id'     => $taxId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('price_tax')->insert($rows);
    }

    /* ── Blocs d'inventaire ───────────────────────────────────────────────── */

    private function insertInventoryBlocks($now): void
    {
        foreach (self::BLOCK_HOSTELS as $i => $hostelId) {
            $this->insertOneBlock($hostelId, $i, $now);
        }
    }

    /**
     * Rotation :
     *   index % 3 == 0 → block sur chambre privée (maintenance)
     *   index % 3 == 1 → block sur lit de dortoir (maintenance matelas)
     *   index % 3 == 2 → block sur espace tente  (manual_block, ou lit si pas de tente)
     */
    private function insertOneBlock(int $hostelId, int $index, $now): void
    {
        $pattern = $index % 3;

        if ($pattern === 0) {
            $room = DB::table('rooms')
                ->where('hostel_id', $hostelId)
                ->where('type', 'private')
                ->first();

            if (!$room) return;

            DB::table('inventory_blocks')->insert([
                'hostel_id'      => $hostelId,
                'blockable_type' => 'App\Models\Room',
                'blockable_id'   => $room->id,
                'block_type'     => 'maintenance',
                'start_date'     => '2026-05-10',
                'end_date'       => '2026-05-25',
                'reason'         => 'Rénovation salle de bains',
                'note'           => 'Travaux de plomberie planifiés — chambre non disponible.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

        } elseif ($pattern === 1) {
            $dorm = DB::table('rooms')
                ->where('hostel_id', $hostelId)
                ->where('type', 'dormitory')
                ->first();

            if (!$dorm) return;

            $bed = DB::table('beds')->where('room_id', $dorm->id)->first();

            if (!$bed) return;

            DB::table('inventory_blocks')->insert([
                'hostel_id'      => $hostelId,
                'blockable_type' => 'App\Models\Bed',
                'blockable_id'   => $bed->id,
                'block_type'     => 'maintenance',
                'start_date'     => '2026-05-12',
                'end_date'       => '2026-05-20',
                'reason'         => 'Remplacement matelas',
                'note'           => 'Commande en cours chez le fournisseur.',
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

        } else {
            $tent = DB::table('tent_spaces')->where('hostel_id', $hostelId)->first();

            if ($tent) {
                DB::table('inventory_blocks')->insert([
                    'hostel_id'      => $hostelId,
                    'blockable_type' => 'App\Models\TentSpace',
                    'blockable_id'   => $tent->id,
                    'block_type'     => 'manual_block',
                    'start_date'     => '2026-05-15',
                    'end_date'       => '2026-05-21',
                    'reason'         => 'Nettoyage et inspection de la zone',
                    'note'           => 'Zone interdite au public pendant la période d\'inspection.',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]);
            } else {
                // Fallback : deuxième lit du premier dortoir
                $dorm = DB::table('rooms')
                    ->where('hostel_id', $hostelId)
                    ->where('type', 'dormitory')
                    ->first();

                if (!$dorm) return;

                $bed = DB::table('beds')->where('room_id', $dorm->id)->skip(1)->first();

                if (!$bed) return;

                DB::table('inventory_blocks')->insert([
                    'hostel_id'      => $hostelId,
                    'blockable_type' => 'App\Models\Bed',
                    'blockable_id'   => $bed->id,
                    'block_type'     => 'manual_block',
                    'start_date'     => '2026-05-15',
                    'end_date'       => '2026-05-22',
                    'reason'         => 'Réservation groupe scolaire',
                    'note'           => 'Lit bloqué pour usage exclusif du groupe.',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]);
            }
        }
    }
}
