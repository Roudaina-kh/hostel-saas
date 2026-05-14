<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * DemoOwnerSeeder — crée les 6 owners avec IDs EXPLICITES (1 à 6).
 *
 * ⚠️ FIX (post-bug FK constraint) :
 *    Plutôt que de laisser MySQL choisir les IDs via auto_increment
 *    (qui peut donner 7,8,9,10,11 après un simple DELETE),
 *    on insère ici avec id=1,2,3,4,5,6 explicitement.
 *
 *    Pour que ça marche, la table doit avoir été TRUNCATE (pas DELETE)
 *    par DemoResetSeeder → c'est garanti par le fix du reset seeder.
 *
 * Mot de passe : password123 (hash bcrypt $2y$12$... — JAMAIS $2b$ car Laravel rejette)
 */
class DemoOwnerSeeder extends Seeder
{
    /** Hash bcrypt pour "password123" — format $2y$ compatible Laravel uniquement. */
    private const PASSWORD_HASH = '$2y$12$FqIBcTqTM.PqPeh5OpZUzegVmmKuZSM93tI6sHbgcG0jDZaerNAh2';

    public function run(): void
    {
        $this->command->info('👤 [DemoOwnerSeeder] Création des 6 owners (IDs explicites 1-6)...');

        $now = now();
        $hasEmailVerified = Schema::hasColumn('owners', 'email_verified_at');

        $owners = [
            ['id' => 1, 'name' => 'Roudaina Ben Salah', 'email' => 'roudaina@hostelflow.com'],
            ['id' => 2, 'name' => 'Karim Trabelsi',     'email' => 'karim@hostelflow.com'],
            ['id' => 3, 'name' => 'Amira Bouazizi',     'email' => 'amira@hostelflow.com'],
            ['id' => 4, 'name' => 'Mehdi Sassi',        'email' => 'mehdi@hostelflow.com'],
            ['id' => 5, 'name' => 'Salma Khelifi',      'email' => 'salma@hostelflow.com'],
            ['id' => 6, 'name' => 'Youssef Belhaj',     'email' => 'youssef@hostelflow.com'],
        ];

        $rows = [];
        foreach ($owners as $owner) {
            $row = [
                'id'         => $owner['id'],
                'name'       => $owner['name'],
                'email'      => $owner['email'],
                'password'   => self::PASSWORD_HASH,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($hasEmailVerified) {
                $row['email_verified_at'] = $now;
            }

            $rows[] = $row;
            $this->command->line("   ↳ id={$owner['id']} | {$owner['name']} → {$owner['email']}");
        }

        DB::table('owners')->insert($rows);

        $this->command->info('✓ 6 owners en base avec IDs garantis 1-6.');
    }
}