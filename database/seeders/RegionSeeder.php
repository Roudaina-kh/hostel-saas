<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        // ── Gouvernorats de Tunisie ───────────────────────────────────────────
        $gouvernorats = [
            ['name' => 'Tunis',         'lat' => 36.8189,  'lng' => 10.1658],
            ['name' => 'Ariana',        'lat' => 36.8625,  'lng' => 10.1956],
            ['name' => 'Ben Arous',     'lat' => 36.7531,  'lng' => 10.2282],
            ['name' => 'Manouba',       'lat' => 36.8086,  'lng' => 10.0978],
            ['name' => 'Bizerte',       'lat' => 37.2746,  'lng' => 9.8739],
            ['name' => 'Nabeul',        'lat' => 36.4561,  'lng' => 10.7376],
            ['name' => 'Zaghouan',      'lat' => 36.4021,  'lng' => 10.1429],
            ['name' => 'Béja',          'lat' => 36.7256,  'lng' => 9.1817],
            ['name' => 'Jendouba',      'lat' => 36.5012,  'lng' => 8.7780],
            ['name' => 'Le Kef',        'lat' => 36.1826,  'lng' => 8.7146],
            ['name' => 'Siliana',       'lat' => 36.0844,  'lng' => 9.3707],
            ['name' => 'Sousse',        'lat' => 35.8256,  'lng' => 10.6369],
            ['name' => 'Monastir',      'lat' => 35.7776,  'lng' => 10.8262],
            ['name' => 'Mahdia',        'lat' => 35.5047,  'lng' => 11.0622],
            ['name' => 'Sfax',          'lat' => 34.7400,  'lng' => 10.7600],
            ['name' => 'Kairouan',      'lat' => 35.6781,  'lng' => 10.0963],
            ['name' => 'Kasserine',     'lat' => 35.1676,  'lng' => 8.8365],
            ['name' => 'Sidi Bouzid',   'lat' => 35.0381,  'lng' => 9.4858],
            ['name' => 'Gabès',         'lat' => 33.8881,  'lng' => 10.0975],
            ['name' => 'Médenine',      'lat' => 33.3549,  'lng' => 10.5055],
            ['name' => 'Tataouine',     'lat' => 32.9211,  'lng' => 10.4518],
            ['name' => 'Gafsa',         'lat' => 34.4250,  'lng' => 8.7842],
            ['name' => 'Tozeur',        'lat' => 33.9197,  'lng' => 8.1335],
            ['name' => 'Kébili',        'lat' => 33.7062,  'lng' => 8.9689],
        ];

        $createdGouvernorats = [];

        foreach ($gouvernorats as $g) {
            $region = Region::updateOrCreate(
                ['slug' => Str::slug($g['name']), 'parent_id' => null],
                [
                    'name'      => $g['name'],
                    'type'      => 'gouvernorat',
                    'latitude'  => $g['lat'],
                    'longitude' => $g['lng'],
                ]
            );
            $createdGouvernorats[$g['name']] = $region->id;
        }

        // ── Villes par gouvernorat (liste complète) ──────────────────────────
        $villes = [
            'Tunis' => [
                ['name' => 'Tunis',              'lat' => 36.8189, 'lng' => 10.1658],
                ['name' => 'Le Bardo',           'lat' => 36.8090, 'lng' => 10.1390],
                ['name' => 'La Marsa',           'lat' => 36.8781, 'lng' => 10.3237],
                ['name' => 'Carthage',           'lat' => 36.8528, 'lng' => 10.3239],
                ['name' => 'Sidi Bou Saïd',      'lat' => 36.8686, 'lng' => 10.3417],
                ['name' => 'La Goulette',        'lat' => 36.8178, 'lng' => 10.3067],
                ['name' => 'Ezzahra',            'lat' => 36.7411, 'lng' => 10.3068],
                ['name' => 'El Omrane',          'lat' => 36.8204, 'lng' => 10.1646],
                ['name' => 'El Menzah',          'lat' => 36.8367, 'lng' => 10.1742],
                ['name' => 'Cité Ettahrir',      'lat' => 36.8336, 'lng' => 10.1855],
            ],
            'Ariana' => [
                ['name' => 'Ariana',             'lat' => 36.8625, 'lng' => 10.1956],
                ['name' => 'Raoued',             'lat' => 36.9047, 'lng' => 10.2461],
                ['name' => 'Soukra',             'lat' => 36.8920, 'lng' => 10.2350],
                ['name' => 'Kalaat El Andalous', 'lat' => 37.0117, 'lng' => 10.1842],
                ['name' => 'Ettadhamen',         'lat' => 36.8581, 'lng' => 10.1183],
                ['name' => 'Mnihla',             'lat' => 36.8536, 'lng' => 10.1131],
                ['name' => 'Sidi Thabet',        'lat' => 36.9150, 'lng' => 10.0436],
            ],
            'Ben Arous' => [
                ['name' => 'Ben Arous',          'lat' => 36.7531, 'lng' => 10.2282],
                ['name' => 'Hammam Lif',         'lat' => 36.7322, 'lng' => 10.3408],
                ['name' => 'Hammam Chott',       'lat' => 36.7172, 'lng' => 10.3431],
                ['name' => 'Radès',              'lat' => 36.7689, 'lng' => 10.2750],
                ['name' => 'Mornag',             'lat' => 36.6750, 'lng' => 10.2911],
                ['name' => 'Fouchana',           'lat' => 36.6836, 'lng' => 10.1714],
                ['name' => 'Mégrine',            'lat' => 36.7708, 'lng' => 10.2400],
                ['name' => 'Mohamedia',          'lat' => 36.6650, 'lng' => 10.1469],
            ],
            'Manouba' => [
                ['name' => 'Manouba',            'lat' => 36.8086, 'lng' => 10.0978],
                ['name' => 'Douar Hicher',       'lat' => 36.8225, 'lng' => 10.0697],
                ['name' => 'Oued Ellil',         'lat' => 36.8358, 'lng' => 10.0539],
                ['name' => 'Tebourba',           'lat' => 36.8333, 'lng' => 9.8333],
                ['name' => 'El Battan',          'lat' => 36.8444, 'lng' => 9.9269],
                ['name' => 'Mornaguia',          'lat' => 36.7556, 'lng' => 10.0036],
            ],
            'Bizerte' => [
                ['name' => 'Bizerte',            'lat' => 37.2746, 'lng' => 9.8739],
                ['name' => 'Menzel Bourguiba',   'lat' => 37.1531, 'lng' => 9.7847],
                ['name' => 'Ras Jebel',          'lat' => 37.2147, 'lng' => 10.1192],
                ['name' => 'Mateur',             'lat' => 37.0411, 'lng' => 9.6644],
                ['name' => 'Sejnane',            'lat' => 37.0556, 'lng' => 9.2381],
                ['name' => 'Utique',             'lat' => 37.0556, 'lng' => 10.0617],
                ['name' => 'Ghar El Melh',       'lat' => 37.1750, 'lng' => 10.1869],
            ],
            'Nabeul' => [
                ['name' => 'Nabeul',             'lat' => 36.4561, 'lng' => 10.7376],
                ['name' => 'Hammamet',           'lat' => 36.4000, 'lng' => 10.6167],
                ['name' => 'Kelibia',            'lat' => 36.8469, 'lng' => 11.1028],
                ['name' => 'Korba',              'lat' => 36.5734, 'lng' => 10.8661],
                ['name' => 'Soliman',            'lat' => 36.6986, 'lng' => 10.4886],
                ['name' => 'Dar Chaabane',       'lat' => 36.4636, 'lng' => 10.7547],
                ['name' => 'Menzel Temime',      'lat' => 36.7806, 'lng' => 10.9789],
                ['name' => 'El Haouaria',        'lat' => 37.0500, 'lng' => 11.0167],
                ['name' => 'Grombalia',          'lat' => 36.6019, 'lng' => 10.5008],
                ['name' => 'Béni Khalled',       'lat' => 36.6447, 'lng' => 10.5872],
            ],
            'Zaghouan' => [
                ['name' => 'Zaghouan',           'lat' => 36.4021, 'lng' => 10.1429],
                ['name' => 'Zriba',              'lat' => 36.3258, 'lng' => 10.1289],
                ['name' => 'El Fahs',            'lat' => 36.3792, 'lng' => 9.9072],
                ['name' => 'Bir Mcherga',        'lat' => 36.5172, 'lng' => 9.9583],
                ['name' => 'Nadhour',            'lat' => 36.4222, 'lng' => 10.4750],
            ],
            'Béja' => [
                ['name' => 'Béja',               'lat' => 36.7256, 'lng' => 9.1817],
                ['name' => 'Testour',            'lat' => 36.5500, 'lng' => 9.4444],
                ['name' => 'Nefza',              'lat' => 36.9700, 'lng' => 9.0769],
                ['name' => 'Medjez El Bab',      'lat' => 36.6500, 'lng' => 9.6100],
                ['name' => 'Téboursouk',         'lat' => 36.4583, 'lng' => 9.2503],
            ],
            'Le Kef' => [
                ['name' => 'Le Kef',             'lat' => 36.1826, 'lng' => 8.7146],
                ['name' => 'Tajerouine',         'lat' => 35.8939, 'lng' => 8.5536],
                ['name' => 'Dahmani',            'lat' => 35.9333, 'lng' => 8.8333],
                ['name' => 'Sakiet Sidi Youssef','lat' => 36.2167, 'lng' => 8.3667],
                ['name' => 'Nebeur',             'lat' => 36.2486, 'lng' => 8.7414],
            ],
            'Jendouba' => [
                ['name' => 'Jendouba',           'lat' => 36.5012, 'lng' => 8.7780],
                ['name' => 'Tabarka',            'lat' => 36.9545, 'lng' => 8.7578],
                ['name' => 'Aïn Draham',         'lat' => 36.7833, 'lng' => 8.6833],
                ['name' => 'Fernana',            'lat' => 36.6597, 'lng' => 8.6953],
                ['name' => 'Bou Salem',          'lat' => 36.6086, 'lng' => 8.9719],
            ],
            'Siliana' => [
                ['name' => 'Siliana',            'lat' => 36.0844, 'lng' => 9.3707],
                ['name' => 'Makthar',            'lat' => 35.8581, 'lng' => 9.2042],
                ['name' => 'Gaâfour',            'lat' => 36.3158, 'lng' => 9.3258],
                ['name' => 'Bouarada',           'lat' => 36.3583, 'lng' => 9.6250],
                ['name' => 'El Krib',            'lat' => 36.2622, 'lng' => 9.1694],
            ],
            'Sousse' => [
                ['name' => 'Sousse',             'lat' => 35.8256, 'lng' => 10.6369],
                ['name' => 'Hammam Sousse',      'lat' => 35.8617, 'lng' => 10.5928],
                ['name' => 'Akouda',             'lat' => 35.8689, 'lng' => 10.5750],
                ['name' => 'Port el Kantaoui',   'lat' => 35.8942, 'lng' => 10.5950],
                ['name' => 'Msaken',             'lat' => 35.7314, 'lng' => 10.5828],
                ['name' => 'Kalaa Kebira',       'lat' => 35.8694, 'lng' => 10.5417],
                ['name' => 'Kalaa Seghira',      'lat' => 35.8536, 'lng' => 10.5722],
            ],
            'Monastir' => [
                ['name' => 'Monastir',           'lat' => 35.7776, 'lng' => 10.8262],
                ['name' => 'Skanes',             'lat' => 35.7981, 'lng' => 10.7658],
                ['name' => 'Moknine',            'lat' => 35.6311, 'lng' => 10.8961],
                ['name' => 'Ksar Hellal',        'lat' => 35.6431, 'lng' => 10.8911],
                ['name' => 'Jemmal',             'lat' => 35.6256, 'lng' => 10.7553],
                ['name' => 'Bekalta',            'lat' => 35.6125, 'lng' => 10.9883],
                ['name' => 'Teboulba',           'lat' => 35.6622, 'lng' => 10.9586],
            ],
            'Mahdia' => [
                ['name' => 'Mahdia',             'lat' => 35.5047, 'lng' => 11.0622],
                ['name' => 'El Jem',             'lat' => 35.2978, 'lng' => 10.7128],
                ['name' => 'Ksour Essef',        'lat' => 35.4150, 'lng' => 10.9933],
                ['name' => 'Chebba',             'lat' => 35.2367, 'lng' => 11.1167],
                ['name' => 'Rejiche',            'lat' => 35.4625, 'lng' => 11.0783],
                ['name' => 'Bou Merdes',         'lat' => 35.2167, 'lng' => 10.8000],
            ],
            'Sfax' => [
                ['name' => 'Sfax',               'lat' => 34.7400, 'lng' => 10.7600],
                ['name' => 'Sakiet Ezzit',       'lat' => 34.7847, 'lng' => 10.7689],
                ['name' => 'Sakiet Eddaier',     'lat' => 34.8000, 'lng' => 10.7556],
                ['name' => 'Kerkennah',          'lat' => 34.7167, 'lng' => 11.2000],
                ['name' => 'Mahres',             'lat' => 34.5311, 'lng' => 10.5044],
                ['name' => 'Jebeniana',          'lat' => 35.0392, 'lng' => 10.9000],
                ['name' => 'Agareb',             'lat' => 34.7461, 'lng' => 10.5275],
            ],
            'Kairouan' => [
                ['name' => 'Kairouan',           'lat' => 35.6781, 'lng' => 10.0963],
                ['name' => 'Sbikha',             'lat' => 35.9333, 'lng' => 10.0167],
                ['name' => 'Haffouz',            'lat' => 35.6411, 'lng' => 9.6750],
                ['name' => 'Oueslatia',          'lat' => 35.8500, 'lng' => 9.6000],
                ['name' => 'Chebika',            'lat' => 35.6911, 'lng' => 9.9183],
            ],
            'Kasserine' => [
                ['name' => 'Kasserine',          'lat' => 35.1676, 'lng' => 8.8365],
                ['name' => 'Sbeitla',            'lat' => 35.2392, 'lng' => 9.1328],
                ['name' => 'Fériana',            'lat' => 34.9486, 'lng' => 8.5722],
                ['name' => 'Thala',              'lat' => 35.5667, 'lng' => 8.6667],
                ['name' => 'Foussana',           'lat' => 35.2667, 'lng' => 8.6500],
            ],
            'Sidi Bouzid' => [
                ['name' => 'Sidi Bouzid',        'lat' => 35.0381, 'lng' => 9.4858],
                ['name' => 'Meknassy',           'lat' => 34.6275, 'lng' => 9.6128],
                ['name' => 'Regueb',             'lat' => 34.8750, 'lng' => 9.7861],
                ['name' => 'Jelma',              'lat' => 35.2536, 'lng' => 9.5586],
                ['name' => 'Bir El Hafey',       'lat' => 34.9667, 'lng' => 9.2000],
            ],
            'Gabès' => [
                ['name' => 'Gabès',              'lat' => 33.8881, 'lng' => 10.0975],
                ['name' => 'Métouia',            'lat' => 33.9656, 'lng' => 10.0011],
                ['name' => 'El Hamma',           'lat' => 33.8869, 'lng' => 9.7950],
                ['name' => 'Mareth',             'lat' => 33.6306, 'lng' => 10.2906],
                ['name' => 'Ghannouch',          'lat' => 33.9333, 'lng' => 10.1000],
                ['name' => 'Matmata',            'lat' => 33.5444, 'lng' => 9.9710],
            ],
            'Médenine' => [
                ['name' => 'Médenine',           'lat' => 33.3549, 'lng' => 10.5055],
                ['name' => 'Djerba',             'lat' => 33.8075, 'lng' => 10.9913],
                ['name' => 'Houmt Souk',         'lat' => 33.8753, 'lng' => 10.8573],
                ['name' => 'Midoun',             'lat' => 33.8089, 'lng' => 10.9989],
                ['name' => 'Zarzis',             'lat' => 33.5032, 'lng' => 11.1124],
                ['name' => 'Ben Gardane',        'lat' => 33.1383, 'lng' => 11.2189],
            ],
            'Tataouine' => [
                ['name' => 'Tataouine',          'lat' => 32.9211, 'lng' => 10.4518],
                ['name' => 'Ghomrassen',         'lat' => 33.0589, 'lng' => 10.3411],
                ['name' => 'Remada',             'lat' => 32.3167, 'lng' => 10.4000],
                ['name' => 'Bir Lahmar',         'lat' => 33.0083, 'lng' => 10.2103],
                ['name' => 'Dhehiba',            'lat' => 32.0181, 'lng' => 10.7011],
            ],
            'Gafsa' => [
                ['name' => 'Gafsa',              'lat' => 34.4250, 'lng' => 8.7842],
                ['name' => 'Métlaoui',           'lat' => 34.3236, 'lng' => 8.4014],
                ['name' => 'Redeyef',            'lat' => 34.3833, 'lng' => 8.1500],
                ['name' => 'Mdhilla',            'lat' => 34.2469, 'lng' => 8.6083],
                ['name' => 'El Guettar',         'lat' => 34.3411, 'lng' => 8.9367],
            ],
            'Tozeur' => [
                ['name' => 'Tozeur',             'lat' => 33.9197, 'lng' => 8.1335],
                ['name' => 'Nefta',              'lat' => 33.8731, 'lng' => 7.8778],
                ['name' => 'Degache',            'lat' => 33.9789, 'lng' => 8.2086],
                ['name' => 'Tamerza',            'lat' => 34.3833, 'lng' => 7.9500],
            ],
            'Kébili' => [
                ['name' => 'Kébili',             'lat' => 33.7062, 'lng' => 8.9689],
                ['name' => 'Douz',               'lat' => 33.4561, 'lng' => 9.0256],
                ['name' => 'Souk Lahad',         'lat' => 33.7489, 'lng' => 8.8856],
                ['name' => 'El Faouar',          'lat' => 33.3375, 'lng' => 8.7050],
            ],
        ];

        foreach ($villes as $gouvernoratName => $villesList) {
            $parentId = $createdGouvernorats[$gouvernoratName] ?? null;
            if (!$parentId) continue;

            foreach ($villesList as $v) {
                // Slug unique : ville-gouvernorat pour éviter tout doublon
                $slug = Str::slug($v['name']) . '-' . Str::slug($gouvernoratName);

                Region::updateOrCreate(
                    ['slug' => $slug, 'parent_id' => $parentId],
                    [
                        'name'      => $v['name'],
                        'type'      => 'ville',
                        'latitude'  => $v['lat'],
                        'longitude' => $v['lng'],
                    ]
                );
            }
        }

        $this->command->info('✅ ' . Region::count() . ' régions au total (gouvernorats + villes).');
    }
}