<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Tunisie',         'code' => 'TN'],
            ['name' => 'France',          'code' => 'FR'],
            ['name' => 'Allemagne',       'code' => 'DE'],
            ['name' => 'Italie',          'code' => 'IT'],
            ['name' => 'Espagne',         'code' => 'ES'],
            ['name' => 'Royaume-Uni',     'code' => 'GB'],
            ['name' => 'États-Unis',      'code' => 'US'],
            ['name' => 'Maroc',           'code' => 'MA'],
            ['name' => 'Algérie',         'code' => 'DZ'],
            ['name' => 'Libye',           'code' => 'LY'],
            ['name' => 'Égypte',          'code' => 'EG'],
            ['name' => 'Canada',          'code' => 'CA'],
            ['name' => 'Australie',       'code' => 'AU'],
            ['name' => 'Pays-Bas',        'code' => 'NL'],
            ['name' => 'Belgique',        'code' => 'BE'],
            ['name' => 'Suisse',          'code' => 'CH'],
            ['name' => 'Portugal',        'code' => 'PT'],
            ['name' => 'Pologne',         'code' => 'PL'],
            ['name' => 'Russie',          'code' => 'RU'],
            ['name' => 'Chine',           'code' => 'CN'],
            ['name' => 'Japon',           'code' => 'JP'],
            ['name' => 'Inde',            'code' => 'IN'],
            ['name' => 'Brésil',          'code' => 'BR'],
            ['name' => 'Autre',           'code' => 'XX'],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate(['code' => $country['code']], $country);
        }
    }
}