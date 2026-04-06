<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Owner;
use App\Models\Hostel;
use App\Models\User;
use App\Models\SuperAdmin;

class SprintOneSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ──────────────────────────────────────
        $superAdmin = SuperAdmin::firstOrCreate(
            ['email' => 'admin@hostelflow.com'],
            [
                'name'      => 'Super Admin',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // ── Owner ────────────────────────────────────────────
        $owner = Owner::firstOrCreate(
            ['email' => 'roudaina@gmail.com'],
            [
                'name'     => 'Roudaina',
                'phone'    => '0612345678',
                'password' => Hash::make('password'),
                'status'   => 'active',
            ]
        );

        // ── Hostel ───────────────────────────────────────────
        $hostel = Hostel::firstOrCreate(
            ['owner_id' => $owner->id, 'name' => 'Hostel Tunis'],
            [
                'email'   => 'contact@hosteltunis.com',
                'phone'   => '71000000',
                'address' => 'Rue de la Liberté',
                'city'    => 'Tunis',
                'country' => 'Tunisia',
                'status'  => 'active',
            ]
        );

        // ── Users internes ───────────────────────────────────
        $users = [
            [
                'name'  => 'Manager Test',
                'email' => 'manager@hostelflow.com',
                'role'  => 'manager',
            ],
            [
                'name'  => 'Staff Test',
                'email' => 'staff@hostelflow.com',
                'role'  => 'staff',
            ],
            [
                'name'  => 'Financial Test',
                'email' => 'financial@hostelflow.com',
                'role'  => 'financial',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name'     => $userData['name'],
                    'phone'    => null,
                    'password' => Hash::make('password'),
                    'status'   => 'active',
                ]
            );

            // Attacher au hostel si pas déjà fait
            if (! $user->hostels()->where('hostels.id', $hostel->id)->exists()) {
                $user->hostels()->attach($hostel->id, [
                    'role'   => $userData['role'],
                    'status' => 'active',
                ]);
            }
        }

        $this->command->info('✅ Sprint 1 — Données de test créées.');
        $this->command->table(
            ['Type', 'Email', 'Mot de passe', 'URL de connexion'],
            [
                ['Super Admin', 'admin@hostelflow.com',    'password', '/super-admin/login'],
                ['Owner',       'roudaina@gmail.com',      'password', '/login'],
                ['Manager',     'manager@hostelflow.com',  'password', '/user/login'],
                ['Staff',       'staff@hostelflow.com',    'password', '/user/login'],
                ['Financial',   'financial@hostelflow.com','password', '/user/login'],
            ]
        );
    }
}