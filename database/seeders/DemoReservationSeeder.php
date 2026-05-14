<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * DemoReservationSeeder — guests → réservations → reservation_people → paiements.
 *
 * 6 profils saisonniers × 5 décalages mensuels = 30 patterns uniques (1 par hostel).
 * Chaque hostel reçoit 34–41 réservations couvrant Jun 2025 – Mai 2026 + 1-2 futures.
 *
 * Profils :
 *   summer   → pic Jul-Août       late_sum → pic Août-Sep
 *   autumn   → pic Oct-Nov        winter   → pic Déc-Jan
 *   spring   → pic Avr-Mai        steady   → trafic régulier
 *
 * Rotation par groupe de 6 hostels : décalage 0→1→2→3→4 mois
 *   → hostel 1 & 7 ont le même profil mais des pics différents de 1 mois.
 *
 * PRÉREQUIS : DemoRoomSeeder + DemoPriceTaxSeeder + DemoUserSeeder.
 */
class DemoReservationSeeder extends Seeder
{
    /** Tarifs TTC (cohérents avec DemoPriceTaxSeeder) */
    private const TTC = [
        'Chambre Simple 1' => 32.10,
        'Chambre Simple 2' => 32.10,
        'Chambre Double 1' => 58.85,
        'Chambre Double 2' => 58.85,
        'Chambre Triple 1' => 80.25,
        'Dortoir 4 lits'   => 16.05,
        'Dortoir 6 lits'   => 13.91,
        'Dortoir 8 lits'   => 12.84,
        'Dortoir 10 lits'  => 10.70,
    ];

    /**
     * Demande mensuelle par profil (index 0=Jun25 … 11=Mai26).
     * Les pics atteignent 8 réservations/mois pour un signal visible sur le graphique.
     */
    private const PROFILES = [
        'summer'   => [3, 8, 7, 3, 1, 0, 1, 1, 2, 4, 3, 2],  // pic Jul-Août
        'late_sum' => [2, 4, 8, 7, 3, 1, 0, 1, 2, 3, 3, 2],  // pic Août-Sep
        'autumn'   => [1, 2, 3, 5, 8, 5, 2, 1, 1, 2, 3, 1],  // pic Oct-Nov
        'winter'   => [1, 1, 2, 2, 3, 5, 8, 8, 4, 2, 1, 3],  // pic Déc-Jan
        'spring'   => [3, 2, 2, 2, 2, 1, 1, 3, 5, 7, 8, 5],  // pic Avr-Mai
        'steady'   => [3, 4, 4, 4, 3, 3, 3, 3, 3, 4, 4, 3],  // trafic régulier
    ];

    /** Chambre préférée par hostel (Double/Triple pour des revenus plus visibles) */
    private const PREFERRED_ROOM = [
         1 => 'Chambre Double 1',  2 => 'Chambre Triple 1',  3 => 'Chambre Double 1',
         4 => 'Chambre Triple 1',  5 => 'Chambre Double 1',  6 => 'Chambre Triple 1',
         7 => 'Chambre Simple 1',  8 => 'Chambre Double 1',  9 => 'Chambre Triple 1',
        10 => 'Chambre Double 1', 11 => 'Chambre Triple 1', 12 => 'Chambre Double 1',
        13 => 'Chambre Simple 1', 14 => 'Chambre Triple 1', 15 => 'Chambre Double 1',
        16 => 'Chambre Simple 1', 17 => 'Chambre Double 1', 18 => 'Chambre Triple 1',
        19 => 'Chambre Double 1', 20 => 'Chambre Simple 1', 21 => 'Chambre Triple 1',
        22 => 'Chambre Double 1', 23 => 'Chambre Simple 1', 24 => 'Chambre Triple 1',
        25 => 'Chambre Double 1', 26 => 'Chambre Simple 1', 27 => 'Chambre Triple 1',
        28 => 'Chambre Double 1', 29 => 'Chambre Triple 1', 30 => 'Chambre Simple 1',
    ];

    /** Sources de réservation (rotation) */
    private const SOURCES = ['direct', 'hostelworld', 'booking.com', 'walk-in', 'direct', 'phone'];

    private int   $tnCountryId = 1;
    private int   $frCountryId = 1;
    private int   $deCountryId = 1;
    private int   $gbCountryId = 1;
    private int   $itCountryId = 1;
    private array $guestIds    = [];
    private array $managerByHostel = [];

    // ─────────────────────────────────────────────────────────────────────────

    public function run(): void
    {
        $this->command->info('🛎️  [DemoReservationSeeder] Guests, réservations et paiements...');
        $now = now();

        $this->resolveCountries();
        $this->createGuests($now);
        $this->resolveManagers();
        $this->createReservations($now);

        $this->command->line('   ↳ guests              : ' . DB::table('guests')->count());
        $this->command->line('   ↳ réservations        : ' . DB::table('reservations')->count());
        $this->command->line('   ↳ reservation_people  : ' . DB::table('reservation_people')->count());
        $this->command->line('   ↳ paiements           : ' . DB::table('payments')->count());
        $this->command->info('✓ Réservations + Paiements OK.');
    }

    /* ── 1. Lookup pays ───────────────────────────────────────────────────── */

    private function resolveCountries(): void
    {
        $lookup = fn(string $code): int => DB::table('countries')->where('code', $code)->value('id') ?? 1;
        $this->tnCountryId = $lookup('TN');
        $this->frCountryId = $lookup('FR');
        $this->deCountryId = $lookup('DE');
        $this->gbCountryId = $lookup('GB');
        $this->itCountryId = $lookup('IT');
    }

    /* ── 2. Création des 20 guests ────────────────────────────────────────── */

    private function createGuests($now): void
    {
        $guests = [
            ['Ahmed',   'Mansour',   'male',   '12345678', 'ahmed.mansour@gmail.com',    '+216 55 123 456', $this->tnCountryId],
            ['Sonia',   'Ben Ali',   'female', '23456789', 'sonia.benali@gmail.com',     '+216 55 234 567', $this->tnCountryId],
            ['Karim',   'Jouini',    'male',   '34567890', 'karim.jouini@gmail.com',     '+216 55 345 678', $this->tnCountryId],
            ['Rania',   'Gharbi',    'female', '45678901', 'rania.gharbi@gmail.com',     '+216 55 456 789', $this->tnCountryId],
            ['Mehdi',   'Triki',     'male',   '56789012', 'mehdi.triki@gmail.com',      '+216 55 567 890', $this->tnCountryId],
            ['Leila',   'Missaoui',  'female', '67890123', 'leila.missaoui@gmail.com',   '+216 55 678 901', $this->tnCountryId],
            ['Yassine', 'Chermiti',  'male',   '78901234', 'yassine.chermiti@gmail.com', '+216 55 789 012', $this->tnCountryId],
            ['Nour',    'Hamdi',     'female', '89012345', 'nour.hamdi@gmail.com',       '+216 55 890 123', $this->tnCountryId],
            ['Amine',   'Saidi',     'male',   '90123456', 'amine.saidi@gmail.com',      '+216 55 901 234', $this->tnCountryId],
            ['Sara',    'Karray',    'female', '01234567', 'sara.karray@gmail.com',      '+216 55 012 345', $this->tnCountryId],
            ['Pierre',  'Dupont',    'male',   'FR123456', 'pierre.dupont@gmail.com',    '+33 6 12 34 56 78', $this->frCountryId],
            ['Marie',   'Martin',    'female', 'FR234567', 'marie.martin@gmail.com',     '+33 6 23 45 67 89', $this->frCountryId],
            ['Hans',    'Mueller',   'male',   'DE123456', 'hans.mueller@gmail.com',     '+49 151 1234567',   $this->deCountryId],
            ['Emma',    'Schmidt',   'female', 'DE234567', 'emma.schmidt@gmail.com',     '+49 151 2345678',   $this->deCountryId],
            ['James',   'Wilson',    'male',   'GB123456', 'james.wilson@gmail.com',     '+44 7911 123456',   $this->gbCountryId],
            ['Sophie',  'Brown',     'female', 'GB234567', 'sophie.brown@gmail.com',     '+44 7911 234567',   $this->gbCountryId],
            ['Marco',   'Ferrari',   'male',   'IT123456', 'marco.ferrari@gmail.com',    '+39 333 1234567',   $this->itCountryId],
            ['Giulia',  'Rossi',     'female', 'IT234567', 'giulia.rossi@gmail.com',     '+39 333 2345678',   $this->itCountryId],
            ['Omar',    'Benhassen', 'male',   '11234567', 'omar.benhassen@gmail.com',   '+216 55 111 222', $this->tnCountryId],
            ['Fatma',   'Zouari',    'female', '22345678', 'fatma.zouari@gmail.com',     '+216 55 222 333', $this->tnCountryId],
        ];

        $this->guestIds = [];
        foreach ($guests as [$first, $last, $gender, $cin, $email, $phone, $countryId]) {
            $this->guestIds[] = DB::table('guests')->insertGetId([
                'first_name'    => $first,
                'last_name'     => $last,
                'identity_card' => $cin,
                'email'         => $email,
                'phone'         => $phone,
                'country_id'    => $countryId,
                'gender'        => $gender,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }

    /* ── 3. Lookup managers ──────────────────────────────────────────────── */

    private function resolveManagers(): void
    {
        $this->managerByHostel = DB::table('hostel_user')
            ->where('role', 'manager')->get()
            ->groupBy('hostel_id')
            ->map(fn($g) => $g->first()->user_id)
            ->toArray();
    }

    /* ── 4. Création des réservations ────────────────────────────────────── */

    private function createReservations($now): void
    {
        // Jun 2025 → Mai 2026 (12 mois)
        $months = [
            0  => [2025, 6],   1  => [2025, 7],   2  => [2025, 8],
            3  => [2025, 9],   4  => [2025, 10],  5  => [2025, 11],
            6  => [2025, 12],  7  => [2026, 1],   8  => [2026, 2],
            9  => [2026, 3],  10  => [2026, 4],  11  => [2026, 5],
        ];

        $profileKeys = array_keys(self::PROFILES);
        $guestCount  = count($this->guestIds);
        $guestIndex  = 0;

        for ($h = 1; $h <= 30; $h++) {
            // Profil : cycle de 6 — hostels 1-6 couvrent tous les profils
            $key   = $profileKeys[($h - 1) % count($profileKeys)];
            // Décalage : +0 mois pour hostels 1-6, +1 pour 7-12, … +4 pour 25-30
            $shift = (int)(($h - 1) / count($profileKeys));

            $demand = self::PROFILES[$key];

            // Rotation gauche du tableau de demande = décale le pic saisonnier
            if ($shift > 0) {
                $demand = array_merge(
                    array_slice($demand, $shift),
                    array_slice($demand, 0, $shift)
                );
            }

            for ($mi = 0; $mi < 12; $mi++) {
                $n = $demand[$mi];
                if ($n === 0) continue;

                [$year, $month] = $months[$mi];

                for ($s = 0; $s < $n; $s++) {
                    // Étale les réservations sur le mois (jours 1-26)
                    $day       = min(1 + (int)(($s * 27) / max($n, 1)) + ($h % 3), 26);
                    $startDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $nights    = $this->nights($h, $mi * 10 + $s);
                    $isDorm    = (($h + $mi + $s) % 4 === 0);
                    $guests    = $isDorm
                        ? (2 + (($h + $mi + $s) % 4))   // 2-5 pers en dortoir
                        : (1 + (($h + $mi + $s) % 3));  // 1-3 pers en chambre
                    $gId       = $this->guestIds[$guestIndex++ % $guestCount];

                    $this->makeReservation(
                        $h, $gId, 'confirmed', $startDate, $nights,
                        $guests, $isDorm ? 'dorm' : 'private', $guestIndex, $now
                    );
                }
            }

            // Réservations futures (Jun-Jul 2026) — sans paiement
            $futureCount = 1 + ($h % 2);
            for ($f = 0; $f < $futureCount; $f++) {
                $fMonth    = 6 + $f;
                $fDay      = min(5 + ($h % 10) + $f * 8, 25);
                $startDate = sprintf('2026-%02d-%02d', $fMonth, $fDay);
                $gId       = $this->guestIds[$guestIndex++ % $guestCount];
                $this->makeReservation(
                    $h, $gId, 'confirmed', $startDate,
                    $this->nights($h, 200 + $f), 1 + ($h % 2), 'private', $guestIndex, $now
                );
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function makeReservation(
        int    $hostelId,
        int    $mainGuestId,
        string $status,
        string $startDate,
        int    $nights,
        int    $totalGuests,
        string $roomType,
        int    $sourceIndex,
        $now
    ): void {
        $endDate = date('Y-m-d', strtotime("$startDate +{$nights} days"));
        $userId  = $this->managerByHostel[$hostelId] ?? 1;
        $source  = self::SOURCES[$sourceIndex % count(self::SOURCES)];

        if ($roomType === 'private') {
            $preferred = self::PREFERRED_ROOM[$hostelId] ?? 'Chambre Double 1';
            $room = DB::table('rooms')
                ->where('hostel_id', $hostelId)
                ->where('type', 'private')
                ->where('name', $preferred)
                ->first()
                ?? DB::table('rooms')
                    ->where('hostel_id', $hostelId)
                    ->where('type', 'private')
                    ->first();

            if (!$room) return;

            $priceNight = self::TTC[$room->name] ?? 58.85;
            $totalTnd   = round($priceNight * $nights, 3);
            $itemType   = 'room';
            $itemId     = $room->id;
        } else {
            $dormNames = ['Dortoir 4 lits', 'Dortoir 6 lits', 'Dortoir 8 lits', 'Dortoir 10 lits'];
            $preferred = $dormNames[$hostelId % count($dormNames)];

            $dorm = DB::table('rooms')
                ->where('hostel_id', $hostelId)
                ->where('type', 'dormitory')
                ->where('name', $preferred)
                ->first()
                ?? DB::table('rooms')
                    ->where('hostel_id', $hostelId)
                    ->where('type', 'dormitory')
                    ->first();

            if (!$dorm) return;

            $bed = DB::table('beds')->where('room_id', $dorm->id)->first();
            if (!$bed) return;

            $priceNight = self::TTC[$dorm->name] ?? 13.91;
            $totalTnd   = round($priceNight * $nights * $totalGuests, 3);
            $itemType   = 'bed';
            $itemId     = $bed->id;
        }

        // ── Réservation ───────────────────────────────────────────────────
        $reservationId = DB::table('reservations')->insertGetId([
            'hostel_id'       => $hostelId,
            'main_guest_id'   => $mainGuestId,
            'start_date'      => $startDate,
            'end_date'        => $endDate,
            'nights'          => $nights,
            'total_guests'    => $totalGuests,
            'status'          => $status,
            'source'          => $source,
            'total_price_tnd' => $totalTnd,
            'notes'           => null,
            'created_by'      => 'DemoReservationSeeder',
            'user_id'         => $userId,
            'created_at'      => $now,
            'updated_at'      => $now,
        ]);

        // ── Reservation people ────────────────────────────────────────────
        $personTnd = $totalGuests > 0 ? round($totalTnd / $totalGuests, 3) : $totalTnd;
        for ($p = 0; $p < $totalGuests; $p++) {
            $guestId = ($p === 0) ? $mainGuestId : null;
            DB::table('reservation_people')->insert([
                'reservation_id' => $reservationId,
                'guest_id'       => $guestId,
                'display_name'   => ($p === 0)
                    ? $this->getGuestName($mainGuestId)
                    : 'Accompagnateur ' . $p,
                'item_type'      => $itemType,
                'item_id'        => $itemId,
                'price_tnd'      => $personTnd,
                'price_input'    => $personTnd,
                'currency'       => 'TND',
                'exchange_rate'  => 1.0000,
                'is_checked_in'  => 0,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }

        // ── Paiement : créé pour les réservations passées confirmées ─────
        $isPast = strtotime($startDate) <= strtotime('today');
        if ($status === 'confirmed' && $isPast) {
            DB::table('payments')->insert([
                'reservation_id' => $reservationId,
                'amount_tnd'     => $totalTnd,
                'payment_method' => 'cash',
                'status'         => 'paid',
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Nombre de nuits déterministe par hostel et slot (2–7 nuits).
     */
    private function nights(int $hostelId, int $slot): int
    {
        return 2 + (($hostelId + $slot * 7) % 6);
    }

    private function getGuestName(int $guestId): string
    {
        $guest = DB::table('guests')->where('id', $guestId)->first();
        return $guest ? trim($guest->first_name . ' ' . $guest->last_name) : 'Guest';
    }
}
