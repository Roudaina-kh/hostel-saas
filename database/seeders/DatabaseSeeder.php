<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder — orchestre les seeders de démo HostelFlow.
 *
 * Exécution :  php artisan db:seed
 *
 * Ordre IMPORTANT :
 *   1. Reset           → nettoie tout
 *   2. Owners          → 6 owners
 *   3. Users           → 50 utilisateurs
 *   4. Hostels         → 30 hostels + pivot hostel_user
 *   5. Rooms           → chambres, lits, espaces tentes
 *   6. PriceTax        → taxes, tarifs, price_tax, blocs inventaire
 *   7. Expenses        → dépenses opérationnelles
 *   8. Reservations    → guests, réservations, reservation_people, paiements
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DemoResetSeeder::class,
            DemoOwnerSeeder::class,
            DemoUserSeeder::class,
            DemoHostelSeeder::class,
            DemoRoomSeeder::class,
            DemoPriceTaxSeeder::class,
            DemoExpenseSeeder::class,
            DemoReservationSeeder::class,
            DemoContactSeeder::class,
            DemoExchangeRateSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  🎉 Seed démo HostelFlow terminé avec succès');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  • 6 owners  |  50 users  |  30 hostels');
        $this->command->info('  • ~210 chambres + ~570 lits + 30 espaces tentes');
        $this->command->info('  • 60 taxes  |  ~240 prix  |  10 blocs inventaire');
        $this->command->info('  • ~1100 dépenses opérationnelles (12 mois, variation par hostel)');
        $this->command->info('  • 20 guests  |  ~1200 réservations  |  ~1100 paiements');
        $this->command->info('  • 180 demandes clients  |  270 taux de change');
        $this->command->info('');
        $this->command->info('  🔑 Mot de passe universel : password123');
        $this->command->info('═══════════════════════════════════════════════════════');
    }
}