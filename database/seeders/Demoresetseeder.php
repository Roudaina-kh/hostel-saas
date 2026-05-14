<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * DemoResetSeeder — clean slate complet.
 *
 * ⚠️ FIX (post-bug FK constraint) :
 *    On TRUNCATE owners au lieu de DELETE pour reset l'auto_increment à 1.
 *    Avec un simple DELETE, l'auto_increment continue à partir du max précédent
 *    (ex: 7) → les nouveaux owners ont les IDs 7,8,9,10,11 au lieu de 2,3,4,5,6,
 *    ce qui casse les FK des hostels qui pointent vers owner_id 2-6.
 *
 *    Roudaina est ré-insérée explicitement avec id=1 par DemoOwnerSeeder.
 *
 * Préserve :
 *   - regions (référentiel — 24 gouvernorats + ~140 villes)
 *   - countries (référentiel)
 *
 * Pattern : Schema::disableForeignKeyConstraints() pour éviter les cascades
 *           imprévisibles. On nettoie dans l'ordre enfant → parent.
 */
class DemoResetSeeder extends Seeder
{
    /**
     * Tables à nettoyer dans l'ordre (les plus dépendantes en premier).
     * Si une table n'existe pas (selon la version du projet), on l'ignore silencieusement.
     */
    private array $tablesToClean = [
        // Opérationnel — peuvent référencer hostel/user/owner
        'expenses',
        'extra_stock_movements',
        'reservation_extras',
        'reservation_people',
        'payments',
        'reservations',
        'guests',
        'contact_requests',
        'inventory_blocks',
        'exchange_rates',
        'price_tax',
        'prices',
        'taxes',
        'extras',
        'beds',
        'rooms',
        'tent_spaces',

        // Pivots
        'hostel_user',

        // Entités principales (ordre : enfants avant parents)
        'hostels',
        'users',
        'owners',   // ← AJOUTÉ : truncate complet pour reset l'auto_increment
    ];

    public function run(): void
    {
        $this->command->warn('🧹 [DemoResetSeeder] Nettoyage en cours...');

        Schema::disableForeignKeyConstraints();

        foreach ($this->tablesToClean as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->command->line("   ↳ $table vidée + auto_increment reset");
            }
        }

        Schema::enableForeignKeyConstraints();

        $this->command->info('✓ Base remise à zéro.');
        $this->command->info('   (Roudaina sera ré-insérée avec id=1 par DemoOwnerSeeder)');
    }
}