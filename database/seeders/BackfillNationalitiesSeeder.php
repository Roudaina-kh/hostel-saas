<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Backfill nationality on existing reservation_people rows.
 *
 * Distribution réaliste du tourisme en Tunisie, inspirée des tendances ONTT
 * et de la répartition typique d'une auberge tunisienne mixte. Les chiffres
 * sont calibrés pour reproduire visuellement le graphique cible du dashboard
 * (États-Unis #1, France #2, Allemagne #3, etc.).
 *
 * Ce seeder n'INSÈRE rien — il UPDATE uniquement les lignes existantes pour
 * alimenter le dashboard Analytics. ~5% restent NULL pour le réalisme métier
 * (cas où le staff oublie de renseigner la nationalité au check-in).
 *
 * Pattern PFE : BI-ready denormalization with backward-compatible nullable column.
 */
class BackfillNationalitiesSeeder extends Seeder
{
    /**
     * Distribution pondérée des pays (chiffres = poids approximatif).
     * Total ≈ 2224 ; ajusté automatiquement au nombre réel de lignes.
     */
    private array $distribution = [
        'États-Unis'        => 330,
        'France'            => 220,
        'Allemagne'         => 205,
        'Italie'            => 145,
        'Tunisie'           => 125,
        'Algérie'           => 110,
        'Chine'             => 105,
        'Australie'         => 95,
        'Maroc'             => 88,
        'Canada'            => 85,
        'Japon'             => 80,
        'Espagne'           => 60,
        'Nouvelle-Zélande'  => 55,
        'Royaume-Uni'       => 50,
        'Suisse'            => 48,
        'Turquie'           => 42,
        'Belgique'          => 38,
        'Pays-Bas'          => 34,
        'Libye'             => 30,
        'Pologne'           => 26,
        'Arabie Saoudite'   => 22,
        'Égypte'            => 20,
        'Brésil'            => 18,
        'Corée du Sud'      => 16,
        'Grèce'             => 15,
        'Danemark'          => 13,
        'Irlande'           => 12,
        'Jordanie'          => 11,
        'Iran'              => 10,
        'Malaisie'          => 10,
        'Argentine'         => 9,
        'Autriche'          => 9,
        'Inde'              => 8,
        'Mexique'           => 8,
        'Norvège'           => 8,
        'Suède'             => 7,
        'Afrique du Sud'    => 7,
        'Finlande'          => 7,
        'Irak'              => 6,
        'Roumanie'          => 6,
        'Russie'            => 6,
        'Hongrie'           => 5,
        'Chili'             => 5,
        'Slovaquie'         => 4,
        'Cuba'              => 4,
        'Indonésie'         => 4,
        'Islande'           => 3,
        'Lettonie'          => 3,
        'Portugal'          => 3,
        'Belize'            => 2,
    ];

    public function run(): void
    {
        $total = DB::table('reservation_people')->count();

        if ($total === 0) {
            $this->command->warn('⚠️  Aucune ligne dans reservation_people. Seeder ignoré.');
            return;
        }

        $this->command->info("📊 Backfill nationalités sur {$total} lignes reservation_people...");

        // 1. Construire la piscine pondérée à partir de la distribution
        $pool = [];
        foreach ($this->distribution as $country => $weight) {
            for ($i = 0; $i < $weight; $i++) {
                $pool[] = $country;
            }
        }

        // 2. Ajouter ~5% de NULL pour le réalisme métier
        $nullCount = (int) round($total * 0.05);
        for ($i = 0; $i < $nullCount; $i++) {
            $pool[] = null;
        }

        // 3. Mélange aléatoire (résultats variés à chaque exécution)
        shuffle($pool);
        $poolSize = count($pool);

        // 4. Mise à jour par chunks dans une transaction (rapide + atomique)
        $bar = $this->command->getOutput()->createProgressBar($total);
        $bar->start();

        DB::transaction(function () use ($pool, $poolSize, $bar) {
            $pointer = 0;

            DB::table('reservation_people')
                ->orderBy('id')
                ->chunkById(500, function ($rows) use (&$pointer, $pool, $poolSize, $bar) {
                    foreach ($rows as $row) {
                        DB::table('reservation_people')
                            ->where('id', $row->id)
                            ->update([
                                'nationality' => $pool[$pointer % $poolSize] ?? null,
                            ]);
                        $pointer++;
                        $bar->advance();
                    }
                });
        });

        $bar->finish();
        $this->command->newLine(2);

        // 5. Statistiques de vérification (visible en console après le run)
        $filled = DB::table('reservation_people')->whereNotNull('nationality')->count();
        $nulled = $total - $filled;

        $topCountries = DB::table('reservation_people')
            ->whereNotNull('nationality')
            ->select('nationality', DB::raw('COUNT(*) as cnt'))
            ->groupBy('nationality')
            ->orderByDesc('cnt')
            ->limit(5)
            ->get();

        $this->command->info("✅ {$filled} remplies / {$nulled} NULL sur {$total} total");
        $this->command->info('🏆 Top 5 pays :');
        foreach ($topCountries as $row) {
            $this->command->line("   • {$row->nationality} : {$row->cnt}");
        }
        $this->command->newLine();
    }
}