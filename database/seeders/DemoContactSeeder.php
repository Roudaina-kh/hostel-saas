<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * DemoContactSeeder — demandes clients (contact_requests) pour les 30 hostels.
 *
 * Volume : 6 demandes par hostel = 180 au total.
 *
 * Répartition des statuts par hostel :
 *   2 × new       → demandes récentes non traitées
 *   1 × read      → lue, pas encore répondue
 *   1 × replied   → réponse envoyée
 *   1 × confirmed → réservation confirmée suite à la demande
 *   1 × cancelled → annulation client
 *
 * Les noms sont tirés d'un pool de prénoms/noms tunisiens (40 × 20 = 800 combinaisons).
 * Les dates d'arrivée couvrent l'été 2026 (juin → septembre).
 *
 * PRÉREQUIS : DemoHostelSeeder doit avoir tourné (hostels ids 1→30).
 */
class DemoContactSeeder extends Seeder
{
    // ── Pool de prénoms et noms tunisiens ──────────────────────────────────

    private const FIRST_NAMES_M = [
        'Ahmed', 'Karim', 'Mohamed', 'Yassine', 'Amine',
        'Mehdi', 'Omar', 'Bilel', 'Sami', 'Rami',
        'Fares', 'Hatem', 'Lotfi', 'Nizar', 'Walid',
        'Taher', 'Maher', 'Adel', 'Slim', 'Mounir',
    ];

    private const FIRST_NAMES_F = [
        'Sonia', 'Rania', 'Nour', 'Leila', 'Sara',
        'Fatma', 'Ines', 'Amira', 'Rim', 'Asma',
        'Sirine', 'Emna', 'Olfa', 'Hana', 'Nesrine',
        'Wissal', 'Dina', 'Mariem', 'Imen', 'Wafa',
    ];

    private const LAST_NAMES = [
        'Ben Ali', 'Trabelsi', 'Karray', 'Hamdi', 'Jouini',
        'Gharbi', 'Missaoui', 'Chermiti', 'Saidi', 'Belhaj',
        'Zouari', 'Mansour', 'Chaabane', 'Lassoued', 'Meddeb',
        'Ben Salah', 'Jebali', 'Belaid', 'Oueslati', 'Chtioui',
    ];

    /** Statuts des 6 demandes (dans l'ordre) */
    private const STATUSES = ['new', 'new', 'read', 'replied', 'confirmed', 'cancelled'];

    /** Types de chambre demandés */
    private const ROOM_TYPES = [
        'Chambre privée', 'Dortoir', 'Chambre privée', 'Dortoir',
        'Chambre simple', 'Espace tente',
    ];

    /** Nombre de voyageurs par demande */
    private const TRAVELERS = [1, 2, 3, 2, 4, 1];

    /** Durées de séjour en nuits */
    private const NIGHTS = [3, 5, 7, 4, 6, 2];

    /** Dates d'arrivée (été 2026) */
    private const ARRIVALS = [
        '2026-06-05', '2026-06-20', '2026-07-03',
        '2026-07-18', '2026-08-08', '2026-08-25',
    ];

    /** Templates de messages en français */
    private const MESSAGES = [
        'Bonjour, je souhaite réserver un hébergement pour %d personne(s) du %s au %s. Merci de confirmer la disponibilité.',
        'Bonjour, pourriez-vous me donner plus d\'informations sur vos tarifs et disponibilités pour le %s ?',
        'Je suis intéressé(e) par une réservation. Avez-vous des places disponibles en %s du %s au %s ?',
        'Bonjour, nous planifions un séjour de %d personne(s). Quelles sont vos conditions d\'annulation ?',
        'Pouvez-vous me confirmer vos disponibilités pour %d voyageur(s) à partir du %s ? Merci.',
        'Bonjour, j\'aimerais obtenir un devis pour %d personne(s) en %s. Cordialement.',
    ];

    // ─────────────────────────────────────────────────────────────────────────

    public function run(): void
    {
        $this->command->info('📩 [DemoContactSeeder] Création des demandes clients...');
        $now = now();

        // Récupère les hostels (city + name)
        $hostels = DB::table('hostels')->select('id', 'city', 'name')->get()->keyBy('id');

        $nameIndex = 0; // Curseur global pour varier les noms

        for ($hostelId = 1; $hostelId <= 30; $hostelId++) {
            $hostel = $hostels[$hostelId] ?? null;
            if (!$hostel) continue;

            $this->insertRequests($hostelId, $hostel->city, $nameIndex, $now);
            $nameIndex += 6;
        }

        $this->command->line('   ↳ demandes total : ' . DB::table('contact_requests')->count() . ' (6/hostel)');
        $this->command->info('✓ Contact requests OK.');
    }

    /* ── Insertion des 6 demandes pour un hostel ──────────────────────────── */

    private function insertRequests(int $hostelId, string $city, int $offset, $now): void
    {
        $mPool = self::FIRST_NAMES_M;
        $fPool = self::FIRST_NAMES_F;
        $lPool = self::LAST_NAMES;
        $mCount = count($mPool);
        $fCount = count($fPool);
        $lCount = count($lPool);

        $rows = [];

        for ($i = 0; $i < 6; $i++) {
            $idx = ($offset + $i);

            // Alternance masculin/féminin
            $isFemale  = ($idx % 2 === 1);
            $firstName = $isFemale
                ? $fPool[$idx % $fCount]
                : $mPool[$idx % $mCount];
            $lastName  = $lPool[$idx % $lCount];

            $arrival    = self::ARRIVALS[$i];
            $nights     = self::NIGHTS[$i];
            $departure  = date('Y-m-d', strtotime("$arrival +{$nights} days"));
            $travelers  = self::TRAVELERS[$i];
            $roomType   = self::ROOM_TYPES[$i];
            $status     = self::STATUSES[$i];

            $firstName_lower = strtolower(str_replace(' ', '.', $firstName));
            $lastName_lower  = strtolower(str_replace([' ', "'"], '.', $lastName));
            $email = "$firstName_lower.$lastName_lower" . ($idx % 5 ?: '') . '@gmail.com';

            $message = $this->buildMessage($i, $travelers, $roomType, $arrival, $departure);

            $rows[] = [
                'hostel_id'      => $hostelId,
                'first_name'     => $firstName,
                'last_name'      => $lastName,
                'email'          => $email,
                'phone'          => '+216 5' . str_pad(($idx * 37 + 1000000) % 9000000 + 1000000, 7, '0', STR_PAD_LEFT),
                'destination'    => $city,
                'arrival_date'   => $arrival,
                'departure_date' => $departure,
                'travelers'      => $travelers,
                'room_type'      => $roomType,
                'message'        => $message,
                'status'         => $status,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        DB::table('contact_requests')->insert($rows);
    }

    private function buildMessage(int $tpl, int $travelers, string $roomType, string $arrival, string $departure): string
    {
        return match ($tpl % 6) {
            0 => sprintf(self::MESSAGES[0], $travelers, $arrival, $departure),
            1 => sprintf(self::MESSAGES[1], $arrival),
            2 => sprintf(self::MESSAGES[2], $roomType, $arrival, $departure),
            3 => sprintf(self::MESSAGES[3], $travelers),
            4 => sprintf(self::MESSAGES[4], $travelers, $arrival),
            default => sprintf(self::MESSAGES[5], $travelers, $roomType),
        };
    }
}
