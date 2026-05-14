<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * DemoHostelSeeder — crée 30 hostels + 90 assignations hostel_user.
 *
 * ARCHITECTURE :
 *
 * 1) Création des 30 hostels (type='hostel', avec lat/lng réelles tunisiennes)
 *    répartis selon le pattern "mix volontaire" :
 *    - Chaque owner couvre des hostels dans 4-5 régions différentes
 *    - Démontre le multi-tenant en stress-test (filtres région, switch hostel)
 *
 * 2) Création des assignations hostel_user :
 *    - 10 managers × 3 hostels (rotation par lots de 3)
 *    - 10 financiers × 3 hostels (même pattern)
 *    - 30 staff × 2 hostels (paires de staff sur paires d'hostels)
 *
 * PRÉREQUIS :
 *    - Table `regions` doit contenir les 24 gouvernorats tunisiens
 *      (lance `php artisan db:seed --class=RegionSeeder` au préalable si besoin)
 *
 * DECISIONS DE DESIGN :
 *
 *  - On utilise DB::table() (Query Builder) au lieu des models Eloquent
 *    pour avoir un contrôle total sur les colonnes insérées (évite les
 *    surprises liées à $fillable / casts / events).
 *
 *  - Tous les hostels sont en type='hostel'. L'utilisateur ajoutera les
 *    tent_spaces manuellement sur les hostels qui ont du camping
 *    (CJ Saniet El Bey à Gabès, CJ Métlaoui, MJ Haïdra, MJ Nefta, etc.)
 *
 *  - La répartition des assignations est volontairement "transversale" :
 *    un manager peut couvrir des hostels appartenant à plusieurs owners
 *    différents. C'est le scénario qui démontre le mieux le pattern
 *    multi-tenant via le pivot many-to-many (hostel_user).
 */
class DemoHostelSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏨 [DemoHostelSeeder] Création des 30 hostels...');

        $regionMap = $this->buildRegionMap();
        $now       = now();

        $hostelsData = $this->hostelsList($regionMap, $now);

        // Insert en bulk
        DB::table('hostels')->insert($hostelsData);

        $this->command->line('   ↳ 30 hostels insérés (ids 1 → 30)');

        // Assignations pivot hostel_user
        $this->command->info('🔗 [DemoHostelSeeder] Création des assignations hostel_user...');
        $this->seedHostelUserPivot($now);

        $this->command->info('✓ Hostels + assignations OK.');
    }

    /* ─────────────────────────────────────────────────────────────────────
     *  ÉTAPE 1 — Lookup des régions (par nom de gouvernorat)
     * ─────────────────────────────────────────────────────────────────── */

    /**
     * Construit un mapping ['NomGouvernorat' => id] pour les 17 gouvernorats utilisés.
     * Échoue avec une exception explicite si une région manque.
     *
     * @return array<string,int>
     */
    private function buildRegionMap(): array
    {
        $neededGouvernorats = [
            'Tunis', 'Béja', 'Sousse', 'Médenine', 'Kébili', 'Nabeul',
            'Jendouba', 'Gabès', 'Kasserine', 'Gafsa', 'Kairouan',
            'Mahdia', 'Sfax', 'Bizerte', 'Kef', 'Monastir', 'Tozeur',
        ];

        $map = [];

        foreach ($neededGouvernorats as $name) {
            $region = DB::table('regions')
                ->where('type', 'gouvernorat')
                ->where(function ($q) use ($name) {
                    // Tolérance : "Kef" ou "Le Kef", "Béja" ou "Beja", etc.
                    $q->where('name', $name)
                      ->orWhere('name', 'Le ' . $name)
                      ->orWhere('name', 'LIKE', '%' . $name . '%');
                })
                ->first();

            if (!$region) {
                throw new \RuntimeException(
                    "❌ Région '$name' (type=gouvernorat) introuvable dans la table `regions`. "
                    . "Lance d'abord `php artisan db:seed --class=RegionSeeder`."
                );
            }

            $map[$name] = $region->id;
        }

        return $map;
    }

    /* ─────────────────────────────────────────────────────────────────────
     *  ÉTAPE 2 — Définition des 30 hostels
     * ─────────────────────────────────────────────────────────────────── */

    /**
     * @param array<string,int> $rm Region map (nom gouvernorat → id)
     * @return array<int,array<string,mixed>>
     */
    private function hostelsList(array $rm, $now): array
    {
        $base = [
            'country'          => 'Tunisia',
            'default_currency' => 'TND',
            'timezone'         => 'Africa/Tunis',
            'is_active'        => 1,
            'status'           => 'active',
            'rating'           => 0,
            'total_reviews'    => 0,
            'cover_image'      => null,
            'type'             => 'hostel',
            'created_at'       => $now,
            'updated_at'       => $now,
        ];

        $hostels = [
            // ═════════════════════════════════════════════════════════════
            //  OWNER 1 — Roudaina Ben Salah (owner_id = 1)
            //  Profil : tourisme culturel/balnéaire, multi-régions premium
            // ═════════════════════════════════════════════════════════════
            [
                'owner_id'    => 1,
                'name'        => 'Complexe de Jeunes La Marsa',
                'region_id'   => $rm['Tunis'],
                'city'        => 'La Marsa',
                'address'     => 'Avenue de la République, La Marsa',
                'phone'       => '+216 71 728 100',
                'email'       => 'la.marsa@hostelflow.com',
                'description' => 'Complexe situé dans une zone côtière prestigieuse adaptée aux séjours culturels (Sidi Bou Saïd, Carthage, plages).',
                'latitude'    => 36.8783,
                'longitude'   => 10.3247,
            ],
            [
                'owner_id'    => 1,
                'name'        => 'Complexe de Jeunes de Béja',
                'region_id'   => $rm['Béja'],
                'city'        => 'Béja',
                'address'     => "Rue de l'Environnement, 9000 Béja",
                'phone'       => '+216 78 453 621',
                'email'       => 'beja@hostelflow.com',
                'description' => "Idéal pour découvrir Dougga, Testour, El Faouar. Activités sportives (tennis, skateboard, randonnée, VTT).",
                'latitude'    => 36.7256,
                'longitude'   => 9.1817,
            ],
            [
                'owner_id'    => 1,
                'name'        => 'Complexe de Jeunes Sousse',
                'region_id'   => $rm['Sousse'],
                'city'        => 'Sousse',
                'address'     => 'Avenue Habib Bourguiba, Sousse',
                'phone'       => '+216 73 225 100',
                'email'       => 'sousse@hostelflow.com',
                'description' => 'Structure proche des plages et de la médina de Sousse.',
                'latitude'    => 35.8256,
                'longitude'   => 10.6411,
            ],
            [
                'owner_id'    => 1,
                'name'        => 'Complexe de Jeunes Houmet Essouk',
                'region_id'   => $rm['Médenine'],
                'city'        => 'Houmet Essouk',
                'address'     => 'Houmet Essouk, Djerba',
                'phone'       => '+216 75 650 200',
                'email'       => 'houmetessouk@hostelflow.com',
                'description' => 'Complexe situé à Djerba permettant de découvrir les richesses culturelles et touristiques de l\'île.',
                'latitude'    => 33.8869,
                'longitude'   => 10.8531,
            ],
            [
                'owner_id'    => 1,
                'name'        => 'Maison de Jeunes Kébili',
                'region_id'   => $rm['Kébili'],
                'city'        => 'Kébili',
                'address'     => '4200 Kébili',
                'phone'       => '+216 75 490 635',
                'email'       => 'kebili@hostelflow.com',
                'description' => 'Découverte du Chott El Jerid et des paysages sahariens. Cuisine, salle de sport, parking.',
                'latitude'    => 33.7050,
                'longitude'   => 8.9690,
            ],

            // ═════════════════════════════════════════════════════════════
            //  OWNER 2 — Karim Trabelsi (owner_id = 2)
            // ═════════════════════════════════════════════════════════════
            [
                'owner_id'    => 2,
                'name'        => 'Complexe de Jeunes Hammamet',
                'region_id'   => $rm['Nabeul'],
                'city'        => 'Hammamet',
                'address'     => 'Avenue de Carthage, Hammamet',
                'phone'       => '+216 72 280 100',
                'email'       => 'hammamet@hostelflow.com',
                'description' => 'Complexe touristique dans une région balnéaire réputée.',
                'latitude'    => 36.4000,
                'longitude'   => 10.6167,
            ],
            [
                'owner_id'    => 2,
                'name'        => 'Maison de Jeunes Aïn Drahem',
                'region_id'   => $rm['Jendouba'],
                'city'        => 'Aïn Drahem',
                'address'     => 'Avenue Habib Bourguiba, Aïn Drahem 8130',
                'phone'       => '+216 78 655 087',
                'email'       => 'aindrahem@hostelflow.com',
                'description' => 'Idéal pour les amateurs de randonnée et de nature (forêts de chêne-liège, Barrage Beni Mtir, Bulla Regia).',
                'latitude'    => 36.7806,
                'longitude'   => 8.6850,
            ],
            [
                'owner_id'    => 2,
                'name'        => 'Complexe de Jeunes Saniet El Bey',
                'region_id'   => $rm['Gabès'],
                'city'        => 'Gabès',
                'address'     => "Rue de l'Oasis, El Bled, 6000 Gabès",
                'phone'       => '+216 75 270 271',
                'email'       => 'sanietelbey@hostelflow.com',
                'description' => "Complexe à Gabès Médina avec terrain de football, salle de sport, studio audio/vidéo et centre de camping. À 2 pas de Matmata.",
                'latitude'    => 33.8814,
                'longitude'   => 10.0982,
            ],
            [
                'owner_id'    => 2,
                'name'        => 'Maison de Jeunes Sbeïtla',
                'region_id'   => $rm['Kasserine'],
                'city'        => 'Sbeïtla',
                'address'     => 'Sbeïtla, Kasserine 1250',
                'phone'       => '+216 77 465 387',
                'email'       => 'sbeitla@hostelflow.com',
                'description' => 'Proche du célèbre site archéologique romain de Sbeïtla.',
                'latitude'    => 35.2333,
                'longitude'   => 9.1167,
            ],
            [
                'owner_id'    => 2,
                'name'        => 'Complexe de Jeunes de Kasserine',
                'region_id'   => $rm['Kasserine'],
                'city'        => 'Kasserine',
                'address'     => 'Cité olympique, Kasserine 1200',
                'phone'       => '+216 77 474 053',
                'email'       => 'kasserine@hostelflow.com',
                'description' => 'Important complexe avec studio radio-TV, terrains sportifs et skateboard. Proche du Parc national Chaambi, Haïdra, Sbeïtla.',
                'latitude'    => 35.1675,
                'longitude'   => 8.8367,
            ],

            // ═════════════════════════════════════════════════════════════
            //  OWNER 3 — Amira Bouazizi (owner_id = 3)
            // ═════════════════════════════════════════════════════════════
            [
                'owner_id'    => 3,
                'name'        => 'Complexe de Jeunes de Gafsa',
                'region_id'   => $rm['Gafsa'],
                'city'        => 'Gafsa',
                'address'     => 'Rue du Caire, cité de la jeunesse, Gafsa 2133',
                'phone'       => '+216 76 224 468',
                'email'       => 'gafsa@hostelflow.com',
                'description' => 'Complexe moderne en centre-ville avec amphithéâtre, radio web, salle de cinéma. Proche du Lézard Rouge et des piscines romaines.',
                'latitude'    => 34.4250,
                'longitude'   => 8.7842,
            ],
            [
                'owner_id'    => 3,
                'name'        => 'Complexe de Jeunes de Kairouan',
                'region_id'   => $rm['Kairouan'],
                'city'        => 'Kairouan',
                'address'     => 'Complexe sportif Hammouda Laouani, Kairouan Nord 3100',
                'phone'       => '+216 77 300 863',
                'email'       => 'kairouan@hostelflow.com',
                'description' => 'Au cœur de la ville historique avec studio radio et salle de réunion. Grande mosquée, médina, Bassins des Aghlabides.',
                'latitude'    => 35.6781,
                'longitude'   => 10.0964,
            ],
            [
                'owner_id'    => 3,
                'name'        => 'Maison de Jeunes Tabarka',
                'region_id'   => $rm['Jendouba'],
                'city'        => 'Tabarka',
                'address'     => 'Route de Tunis Km 0,3, Tabarka 8110',
                'phone'       => '+216 78 671 218',
                'email'       => 'tabarka@hostelflow.com',
                'description' => 'Maison moderne proche de la mer et des forêts de chêne-liège. Fort génois, Festival Jazz de Tabarka.',
                'latitude'    => 36.9544,
                'longitude'   => 8.7589,
            ],
            [
                'owner_id'    => 3,
                'name'        => 'Maison de Jeunes Mahdia',
                'region_id'   => $rm['Mahdia'],
                'city'        => 'Mahdia',
                'address'     => 'Avenue Habib Bourguiba, Mahdia',
                'phone'       => '+216 73 680 200',
                'email'       => 'mahdia@hostelflow.com',
                'description' => 'Région côtière au patrimoine historique riche : médina, cimetière marin, amphithéâtre d\'El Jem à proximité.',
                'latitude'    => 35.5047,
                'longitude'   => 11.0623,
            ],
            [
                'owner_id'    => 3,
                'name'        => 'Complexe de Jeunes Médenine',
                'region_id'   => $rm['Médenine'],
                'city'        => 'Médenine',
                'address'     => 'Avenue de la République, Médenine',
                'phone'       => '+216 75 640 300',
                'email'       => 'medenine@hostelflow.com',
                'description' => 'Destiné aux jeunes voyageurs du sud tunisien. Proche des ksour et villages berbères.',
                'latitude'    => 33.3547,
                'longitude'   => 10.5053,
            ],

            // ═════════════════════════════════════════════════════════════
            //  OWNER 4 — Mehdi Sassi (owner_id = 4)
            // ═════════════════════════════════════════════════════════════
            [
                'owner_id'    => 4,
                'name'        => 'Complexe de Jeunes de Métlaoui',
                'region_id'   => $rm['Gafsa'],
                'city'        => 'Métlaoui',
                'address'     => 'Cité de la Jeunesse, route de Tozeur, Métlaoui 2134',
                'phone'       => '+216 76 242 371',
                'email'       => 'metlaoui@hostelflow.com',
                'description' => 'Connu pour sa proximité avec le train Lézard Rouge et les gorges de Selja. Terrain de football éclairé, randonnée.',
                'latitude'    => 34.3211,
                'longitude'   => 8.4031,
            ],
            [
                'owner_id'    => 4,
                'name'        => 'Maison de Jeunes Jendouba',
                'region_id'   => $rm['Jendouba'],
                'city'        => 'Jendouba',
                'address'     => "Boulevard de l'environnement, Jendouba 8100",
                'phone'       => '+216 78 603 652',
                'email'       => 'jendouba@hostelflow.com',
                'description' => 'Centre-ville avec mini terrain de football et salle de sport. Proche de Bulla Regia et Chemtou.',
                'latitude'    => 36.5011,
                'longitude'   => 8.7800,
            ],
            [
                'owner_id'    => 4,
                'name'        => 'Maison de Jeunes Avenue de Fès',
                'region_id'   => $rm['Kairouan'],
                'city'        => 'Kairouan',
                'address'     => 'Avenue de Fès, Kairouan 3100',
                'phone'       => '+216 77 228 239',
                'email'       => 'av.fes@hostelflow.com',
                'description' => 'Centre sportif avec tennis, football et basket. Proche de la médina de Kairouan.',
                'latitude'    => 35.6800,
                'longitude'   => 10.1020,
            ],
            [
                'owner_id'    => 4,
                'name'        => 'Complexe de Jeunes Route de l\'Aéroport',
                'region_id'   => $rm['Sfax'],
                'city'        => 'Sfax',
                'address'     => "Route de l'Aéroport, Sfax",
                'phone'       => '+216 74 240 100',
                'email'       => 'sfax@hostelflow.com',
                'description' => 'Complexe moderne à Sfax offrant hébergement et infrastructures sportives. Accès aux îles Kerkennah.',
                'latitude'    => 34.7406,
                'longitude'   => 10.7603,
            ],
            [
                'owner_id'    => 4,
                'name'        => 'Maison de Jeunes 15 Octobre Bizerte',
                'region_id'   => $rm['Bizerte'],
                'city'        => 'Bizerte',
                'address'     => 'Avenue Hassen Nouri, Bizerte 7000',
                'phone'       => '+216 72 431 608',
                'email'       => 'bizerte@hostelflow.com',
                'description' => "Installations sportives, club photo, club d'échecs. Vieux port, Lac Ichkeul, Cimetière des Martyrs.",
                'latitude'    => 37.2744,
                'longitude'   => 9.8739,
            ],

            // ═════════════════════════════════════════════════════════════
            //  OWNER 5 — Salma Khelifi (owner_id = 5)
            // ═════════════════════════════════════════════════════════════
            [
                'owner_id'    => 5,
                'name'        => 'Complexe de Jeunes du Kef',
                'region_id'   => $rm['Kef'],
                'city'        => 'Le Kef',
                'address'     => 'Cité Eddir, Le Kef 7100',
                'phone'       => '+216 78 201 214',
                'email'       => 'kef@hostelflow.com',
                'description' => 'Centre-ville pour découvrir les richesses historiques du Kef (Kasbah, Table de Jugurtha, Musée).',
                'latitude'    => 36.1683,
                'longitude'   => 8.7042,
            ],
            [
                'owner_id'    => 5,
                'name'        => 'Maison de Jeunes Hajeb Ayoun',
                'region_id'   => $rm['Kairouan'],
                'city'        => 'Hajeb El Ayoun',
                'address'     => 'Hajeb El Ayoun, Kairouan 3160',
                'phone'       => '+216 77 370 186',
                'email'       => 'hajeb@hostelflow.com',
                'description' => "Salle informatique et terrains sportifs. Proche de Hammam Sidi Maâmer et du Palais Souissïne.",
                'latitude'    => 35.3833,
                'longitude'   => 9.5500,
            ],
            [
                'owner_id'    => 5,
                'name'        => 'Maison de Jeunes Haïdra',
                'region_id'   => $rm['Kasserine'],
                'city'        => 'Haïdra',
                'address'     => 'Cité Intilaka, Haïdra 1221',
                'phone'       => '+216 77 486 300',
                'email'       => 'haidra@hostelflow.com',
                'description' => "Près du site archéologique de Haïdra et d'une vaste zone forestière. Randonnée, VTT.",
                'latitude'    => 35.5667,
                'longitude'   => 8.4500,
            ],
            [
                'owner_id'    => 5,
                'name'        => 'Complexe de Jeunes Ali Skhiri',
                'region_id'   => $rm['Monastir'],
                'city'        => 'Monastir',
                'address'     => 'Cité Ali Skhiri, Monastir',
                'phone'       => '+216 73 460 100',
                'email'       => 'monastir@hostelflow.com',
                'description' => 'Sahel tunisien à proximité des plages et du Ribat de Monastir.',
                'latitude'    => 35.7780,
                'longitude'   => 10.8262,
            ],
            [
                'owner_id'    => 5,
                'name'        => 'Complexe de Jeunes Nabeul',
                'region_id'   => $rm['Nabeul'],
                'city'        => 'Nabeul',
                'address'     => 'Avenue Habib Bourguiba, Nabeul',
                'phone'       => '+216 72 220 100',
                'email'       => 'nabeul@hostelflow.com',
                'description' => 'Complexe accueillant favorisant les activités culturelles et balnéaires (plages, artisanat).',
                'latitude'    => 36.4561,
                'longitude'   => 10.7376,
            ],

            // ═════════════════════════════════════════════════════════════
            //  OWNER 6 — Youssef Belhaj (owner_id = 6)
            // ═════════════════════════════════════════════════════════════
            [
                'owner_id'    => 6,
                'name'        => 'Maison de Jeunes Dahmani',
                'region_id'   => $rm['Kef'],
                'city'        => 'Dahmani',
                'address'     => 'Cité Ibn Khaldoun, Dahmani 7170',
                'phone'       => '+216 78 282 360',
                'email'       => 'dahmani@hostelflow.com',
                'description' => 'Point de départ idéal pour les randonnées dans les montagnes de Dahmani. Festival Sicca Jazz.',
                'latitude'    => 35.9667,
                'longitude'   => 8.8167,
            ],
            [
                'owner_id'    => 6,
                'name'        => 'Maison de Jeunes Nasrallah',
                'region_id'   => $rm['Kairouan'],
                'city'        => 'Nasrallah',
                'address'     => 'Rue Habib Bourguiba, Nasrallah 3170',
                'phone'       => '+216 77 360 045',
                'email'       => 'nasrallah@hostelflow.com',
                'description' => 'Atelier artistique et espaces sportifs. Barrage Sidi Saad à proximité.',
                'latitude'    => 35.3333,
                'longitude'   => 9.8833,
            ],
            [
                'owner_id'    => 6,
                'name'        => 'Maison de Jeunes Kélibia',
                'region_id'   => $rm['Nabeul'],
                'city'        => 'Kélibia',
                'address'     => 'Avenue Habib Bourguiba, Kélibia',
                'phone'       => '+216 72 295 100',
                'email'       => 'kelibia@hostelflow.com',
                'description' => 'Proche des plages et du fort de Kélibia.',
                'latitude'    => 36.8475,
                'longitude'   => 11.0942,
            ],
            [
                'owner_id'    => 6,
                'name'        => 'Maison de Jeunes Korba',
                'region_id'   => $rm['Nabeul'],
                'city'        => 'Korba',
                'address'     => 'Avenue Habib Bourguiba, Korba',
                'phone'       => '+216 72 388 100',
                'email'       => 'korba@hostelflow.com',
                'description' => 'Ville côtière du Cap Bon connue pour ses plages et sa région agricole.',
                'latitude'    => 36.5708,
                'longitude'   => 10.8617,
            ],
            [
                'owner_id'    => 6,
                'name'        => 'Maison de Jeunes Nefta',
                'region_id'   => $rm['Tozeur'],
                'city'        => 'Nefta',
                'address'     => 'Avenue Habib Bourguiba, Nefta',
                'phone'       => '+216 76 430 100',
                'email'       => 'nefta@hostelflow.com',
                'description' => 'Oasis saharienne réputée pour ses paysages désertiques, palmeraies et désert.',
                'latitude'    => 33.8731,
                'longitude'   => 7.8775,
            ],
        ];

        // Merge des valeurs par défaut
        return array_map(fn($h) => array_merge($base, $h), $hostels);
    }

    /* ─────────────────────────────────────────────────────────────────────
     *  ÉTAPE 3 — Assignations hostel_user (pivot many-to-many)
     * ─────────────────────────────────────────────────────────────────── */

    private function seedHostelUserPivot($now): void
    {
        $rows = [];
        $hasStatus     = Schema::hasColumn('hostel_user', 'status');
        $hasTimestamps = Schema::hasColumn('hostel_user', 'created_at');

        // Hostels 1 → 30 supposés (auto-increment depuis truncate)
        $hostels = range(1, 30);

        // Users   1 → 10 : managers
        // Users 11 → 20 : financiers
        // Users 21 → 50 : staff

        // ─── MANAGERS (rotation par 3) ────────────────────────────────────
        // Manager 1 → hostels 1,2,3 | Manager 2 → 4,5,6 | … Manager 10 → 28,29,30
        for ($mi = 0; $mi < 10; $mi++) {
            $userId = $mi + 1;
            $assignedHostels = array_slice($hostels, $mi * 3, 3);

            foreach ($assignedHostels as $hostelId) {
                $rows[] = $this->buildPivotRow($hostelId, $userId, 'manager', $now, $hasStatus, $hasTimestamps);
            }
        }

        // ─── FINANCIERS (même pattern) ────────────────────────────────────
        for ($fi = 0; $fi < 10; $fi++) {
            $userId = 11 + $fi; // financiers user.id 11 → 20
            $assignedHostels = array_slice($hostels, $fi * 3, 3);

            foreach ($assignedHostels as $hostelId) {
                $rows[] = $this->buildPivotRow($hostelId, $userId, 'financial', $now, $hasStatus, $hasTimestamps);
            }
        }

        // ─── STAFF (paires de staff sur paires d'hostels) ─────────────────
        // Paires d'hostels : [1,2], [3,4], [5,6], ..., [29,30] → 15 paires
        // Paires de staff  : [21,22], [23,24], …, [49,50]
        // Pair i : staff (21+2i, 22+2i) → hostels (2i+1, 2i+2)
        for ($pi = 0; $pi < 15; $pi++) {
            $staffA = 21 + ($pi * 2);
            $staffB = 22 + ($pi * 2);
            $hostelA = ($pi * 2) + 1;
            $hostelB = ($pi * 2) + 2;

            foreach ([$staffA, $staffB] as $staffId) {
                foreach ([$hostelA, $hostelB] as $hostelId) {
                    $rows[] = $this->buildPivotRow($hostelId, $staffId, 'staff', $now, $hasStatus, $hasTimestamps);
                }
            }
        }

        DB::table('hostel_user')->insert($rows);

        $this->command->line('   ↳ 30 assignations manager   (10 managers × 3 hostels)');
        $this->command->line('   ↳ 30 assignations financial (10 financiers × 3 hostels)');
        $this->command->line('   ↳ 60 assignations staff     (30 staff × 2 hostels)');
        $this->command->line('   ↳ TOTAL : ' . count($rows) . ' lignes dans hostel_user');
    }

    private function buildPivotRow(
        int $hostelId,
        int $userId,
        string $role,
        $now,
        bool $hasStatus,
        bool $hasTimestamps
    ): array {
        $row = [
            'hostel_id' => $hostelId,
            'user_id'   => $userId,
            'role'      => $role,
        ];

        if ($hasStatus) {
            $row['status'] = 'active';
        }

        if ($hasTimestamps) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        return $row;
    }
}