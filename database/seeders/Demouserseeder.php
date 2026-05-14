<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * DemoUserSeeder — crée 50 utilisateurs (managers + financiers + staff).
 *
 * IMPORTANT : Le rôle (manager/staff/financial) n'est PAS stocké sur la table users.
 *             Il est défini par le pivot hostel_user (voir DemoHostelSeeder).
 *             La table users contient juste l'identité (name/email/password).
 *
 * Convention email : prenom.role@hostelflow.com (ex: ahmed.manager@hostelflow.com)
 *
 * Auto-increment :
 *   users.id 1  → 10 : managers
 *   users.id 11 → 20 : financiers
 *   users.id 21 → 50 : staff
 */
class DemoUserSeeder extends Seeder
{
    private const PASSWORD_HASH = '$2y$12$FqIBcTqTM.PqPeh5OpZUzegVmmKuZSM93tI6sHbgcG0jDZaerNAh2';

    public function run(): void
    {
        $this->command->info('👥 [DemoUserSeeder] Création des 50 utilisateurs...');

        $now = now();
        $hasEmailVerified = Schema::hasColumn('users', 'email_verified_at');

        $managers   = $this->managersList();
        $financiers = $this->financiersList();
        $staff      = $this->staffList();

        $allUsers = [];

        foreach ([
            'manager'   => $managers,
            'finance'   => $financiers,
            'staff'     => $staff,
        ] as $rolePrefix => $list) {
            foreach ($list as $person) {
                $row = [
                    'name'       => $person['name'],
                    'email'      => $person['email'],
                    'password'   => self::PASSWORD_HASH,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if ($hasEmailVerified) {
                    $row['email_verified_at'] = $now;
                }

                $allUsers[] = $row;
            }
        }

        // Insert en bulk (1 seule requête) — plus rapide
        DB::table('users')->insert($allUsers);

        $this->command->line('   ↳ 10 managers   (users.id 1 → 10)');
        $this->command->line('   ↳ 10 financiers (users.id 11 → 20)');
        $this->command->line('   ↳ 30 staff      (users.id 21 → 50)');

        $this->command->info('✓ 50 users insérés.');
    }

    /** @return array<int, array{name: string, email: string}> */
    private function managersList(): array
    {
        return [
            ['name' => 'Ahmed Trabelsi',   'email' => 'ahmed.manager@hostelflow.com'],
            ['name' => 'Fatma Belhaj',     'email' => 'fatma.manager@hostelflow.com'],
            ['name' => 'Sami Khemiri',     'email' => 'sami.manager@hostelflow.com'],
            ['name' => 'Leila Bouazizi',   'email' => 'leila.manager@hostelflow.com'],
            ['name' => 'Walid Sassi',      'email' => 'walid.manager@hostelflow.com'],
            ['name' => 'Nadia Ben Salem',  'email' => 'nadia.manager@hostelflow.com'],
            ['name' => 'Hatem Mejri',      'email' => 'hatem.manager@hostelflow.com'],
            ['name' => 'Sonia Khelifi',    'email' => 'sonia.manager@hostelflow.com'],
            ['name' => 'Rami Ferchichi',   'email' => 'rami.manager@hostelflow.com'],
            ['name' => 'Ines Mansouri',    'email' => 'ines.manager@hostelflow.com'],
        ];
    }

    /** @return array<int, array{name: string, email: string}> */
    private function financiersList(): array
    {
        return [
            ['name' => 'Khaled Gharbi',    'email' => 'khaled.finance@hostelflow.com'],
            ['name' => 'Olfa Riahi',       'email' => 'olfa.finance@hostelflow.com'],
            ['name' => 'Bilel Hamdi',      'email' => 'bilel.finance@hostelflow.com'],
            ['name' => 'Imen Jebali',      'email' => 'imen.finance@hostelflow.com'],
            ['name' => 'Samir Bouzaiene',  'email' => 'samir.finance@hostelflow.com'],
            ['name' => 'Hanene Aouadi',    'email' => 'hanene.finance@hostelflow.com'],
            ['name' => 'Mohamed Daoud',    'email' => 'mohamed.finance@hostelflow.com'],
            ['name' => 'Asma Chaouch',     'email' => 'asma.finance@hostelflow.com'],
            ['name' => 'Fares Romdhani',   'email' => 'fares.finance@hostelflow.com'],
            ['name' => 'Donia Hammami',    'email' => 'donia.finance@hostelflow.com'],
        ];
    }

    /** @return array<int, array{name: string, email: string}> */
    private function staffList(): array
    {
        return [
            ['name' => 'Ali Naceur',        'email' => 'ali.staff@hostelflow.com'],
            ['name' => 'Rim Souissi',       'email' => 'rim.staff@hostelflow.com'],
            ['name' => 'Hamza Maaloul',     'email' => 'hamza.staff@hostelflow.com'],
            ['name' => 'Yasmine Slimani',   'email' => 'yasmine.staff@hostelflow.com'],
            ['name' => 'Anis Bouzid',       'email' => 'anis.staff@hostelflow.com'],
            ['name' => 'Sirine Ouali',      'email' => 'sirine.staff@hostelflow.com'],
            ['name' => 'Omar Khalifa',      'email' => 'omar.staff@hostelflow.com'],
            ['name' => 'Ghada Mejbri',      'email' => 'ghada.staff@hostelflow.com'],
            ['name' => 'Yassine Zribi',     'email' => 'yassine.staff@hostelflow.com'],
            ['name' => 'Hiba Hamdouni',     'email' => 'hiba.staff@hostelflow.com'],
            ['name' => 'Skander Tlili',     'email' => 'skander.staff@hostelflow.com'],
            ['name' => 'Maissa Karoui',     'email' => 'maissa.staff@hostelflow.com'],
            ['name' => 'Aymen Brahmi',      'email' => 'aymen.staff@hostelflow.com'],
            ['name' => 'Dorra Mhamdi',      'email' => 'dorra.staff@hostelflow.com'],
            ['name' => 'Houssem Sfar',      'email' => 'houssem.staff@hostelflow.com'],
            ['name' => 'Cyrine Abidi',      'email' => 'cyrine.staff@hostelflow.com'],
            ['name' => 'Tarek Jaouadi',     'email' => 'tarek.staff@hostelflow.com'],
            ['name' => 'Aida Ksibi',        'email' => 'aida.staff@hostelflow.com'],
            ['name' => 'Khalil Triki',      'email' => 'khalil.staff@hostelflow.com'],
            ['name' => 'Manel Khouaja',     'email' => 'manel.staff@hostelflow.com'],
            ['name' => 'Mahmoud Naffouti',  'email' => 'mahmoud.staff@hostelflow.com'],
            ['name' => 'Wafa Saidi',        'email' => 'wafa.staff@hostelflow.com'],
            ['name' => 'Slim Hadj Ali',     'email' => 'slim.staff@hostelflow.com'],
            ['name' => 'Sondes Boujelben',  'email' => 'sondes.staff@hostelflow.com'],
            ['name' => 'Wassim Abbassi',    'email' => 'wassim.staff@hostelflow.com'],
            ['name' => 'Soumaya Cherif',    'email' => 'soumaya.staff@hostelflow.com'],
            ['name' => 'Issam Bouaziz',     'email' => 'issam.staff@hostelflow.com'],
            ['name' => 'Rania Letaief',     'email' => 'rania.staff@hostelflow.com'],
            ['name' => 'Marouane Smaali',   'email' => 'marouane.staff@hostelflow.com'],
            ['name' => 'Selma Aloui',       'email' => 'selma.staff@hostelflow.com'],
        ];
    }
}