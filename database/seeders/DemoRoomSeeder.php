<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * DemoRoomSeeder — peuple rooms, beds et tent_spaces pour les 30 hostels.
 *
 * Structure par hostel :
 *   Complexe de Jeunes (ids 1,2,3,4,6,8,10,11,12,15,16,19,21,24,25)
 *     → 5 chambres privées (2 simples + 2 doubles + 1 triple)
 *     → 4 dortoirs (4, 6, 8, 10 lits)
 *
 *   Maison de Jeunes (ids 5,7,9,13,14,17,18,20,22,23,26,27,28,29,30)
 *     → 3 chambres privées (1 simple + 2 doubles)
 *     → 2 dortoirs (4, 6 lits)
 *
 *   Hostels 1→15 reçoivent en plus 2 espaces tentes chacun.
 *
 * PRÉREQUIS : DemoHostelSeeder doit avoir tourné (hostels ids 1→30 en base).
 */
class DemoRoomSeeder extends Seeder
{
    /** IDs des Complexes de Jeunes (plus grands établissements) */
    private const COMPLEXE_IDS = [1, 2, 3, 4, 6, 8, 10, 11, 12, 15, 16, 19, 21, 24, 25];

    /** 15 premiers hostels reçoivent des espaces tentes */
    private const TENT_HOSTEL_IDS = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];

    public function run(): void
    {
        $this->command->info('🛏️  [DemoRoomSeeder] Création des chambres, lits et espaces tentes...');

        $now = now();

        for ($hostelId = 1; $hostelId <= 30; $hostelId++) {
            $isLarge = in_array($hostelId, self::COMPLEXE_IDS);
            $this->seedRooms($hostelId, $isLarge, $now);

            if (in_array($hostelId, self::TENT_HOSTEL_IDS)) {
                $this->seedTentSpaces($hostelId, $now);
            }
        }

        $privateCount  = DB::table('rooms')->where('type', 'private')->count();
        $dormCount     = DB::table('rooms')->where('type', 'dormitory')->count();
        $bedCount      = DB::table('beds')->count();
        $tentCount     = DB::table('tent_spaces')->count();

        $this->command->line("   ↳ Chambres privées : $privateCount");
        $this->command->line("   ↳ Dortoirs         : $dormCount");
        $this->command->line("   ↳ Lits (dortoirs)  : $bedCount");
        $this->command->line("   ↳ Espaces tentes   : $tentCount (15 hostels × 2)");
        $this->command->info('✓ Rooms + Beds + TentSpaces OK.');
    }

    /* ─────────────────────────────────────────────────────────────────────
     *  Chambres + Lits
     * ─────────────────────────────────────────────────────────────────── */

    private function seedRooms(int $hostelId, bool $isLarge, $now): void
    {
        $this->insertPrivateRooms($hostelId, $isLarge, $now);
        $this->insertDormitoryRooms($hostelId, $isLarge, $now);
    }

    private function insertPrivateRooms(int $hostelId, bool $isLarge, $now): void
    {
        $rooms = $isLarge
            ? [
                ['Chambre Simple 1', 1],
                ['Chambre Simple 2', 1],
                ['Chambre Double 1', 2],
                ['Chambre Double 2', 2],
                ['Chambre Triple 1', 3],
            ]
            : [
                ['Chambre Simple 1', 1],
                ['Chambre Double 1', 2],
                ['Chambre Double 2', 2],
            ];

        foreach ($rooms as [$name, $max]) {
            DB::table('rooms')->insert([
                'hostel_id'    => $hostelId,
                'name'         => $name,
                'type'         => 'private',
                'max_capacity' => $max,
                'is_enabled'   => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }
    }

    private function insertDormitoryRooms(int $hostelId, bool $isLarge, $now): void
    {
        $dorms = $isLarge
            ? [
                ['Dortoir 4 lits',   4],
                ['Dortoir 6 lits',   6],
                ['Dortoir 8 lits',   8],
                ['Dortoir 10 lits', 10],
            ]
            : [
                ['Dortoir 4 lits', 4],
                ['Dortoir 6 lits', 6],
            ];

        foreach ($dorms as [$name, $bedCount]) {
            $roomId = DB::table('rooms')->insertGetId([
                'hostel_id'    => $hostelId,
                'name'         => $name,
                'type'         => 'dormitory',
                'max_capacity' => $bedCount,
                'is_enabled'   => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);

            $beds = [];
            for ($i = 1; $i <= $bedCount; $i++) {
                $beds[] = [
                    'room_id'    => $roomId,
                    'name'       => "Lit $i",
                    'is_enabled' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DB::table('beds')->insert($beds);
        }
    }

    /* ─────────────────────────────────────────────────────────────────────
     *  Espaces Tentes
     * ─────────────────────────────────────────────────────────────────── */

    private function seedTentSpaces(int $hostelId, $now): void
    {
        DB::table('tent_spaces')->insert([
            [
                'hostel_id'   => $hostelId,
                'name'        => 'Espace Tentes A',
                'max_tents'   => 6,
                'max_persons' => 12,
                'is_enabled'  => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'hostel_id'   => $hostelId,
                'name'        => 'Espace Tentes B',
                'max_tents'   => 4,
                'max_persons' => 8,
                'is_enabled'  => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);
    }
}
