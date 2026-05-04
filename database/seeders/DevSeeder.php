<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ────────────────────────────────────────
        $superAdmin = \App\Models\SuperAdmin::firstOrCreate(
            ['email' => 'admin@hostelflow.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123')
            ]
        );

        // ── Owner ──────────────────────────────────────────────
        $owner = \App\Models\Owner::firstOrCreate(
            ['email' => 'roudaina@hostelflow.com'],
            [
                'name' => 'Roudaina',
                'password' => Hash::make('password123'),
                'status' => 'active'
            ]
        );

        // ── Hostel ─────────────────────────────────────────────
        $hostel = \App\Models\Hostel::firstOrCreate(
            [
                'owner_id' => $owner->id,
                'name' => 'Hostel Rou'
            ],
            [
                'status' => 'active'
            ]
        );

        // ── Manager ────────────────────────────────────────────
        $manager = \App\Models\User::firstOrCreate(
            ['email' => 'manager@hostelflow.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('password123'),
                'status' => 'active'
            ]
        );

        $this->attachUserToHostel($manager, $hostel, 'manager');

        // ── Staff ──────────────────────────────────────────────
        $staff = \App\Models\User::firstOrCreate(
            ['email' => 'staff@hostelflow.com'],
            [
                'name' => 'Staff',
                'password' => Hash::make('password123'),
                'status' => 'active'
            ]
        );

        $this->attachUserToHostel($staff, $hostel, 'staff');

        // ── Financier ──────────────────────────────────────────
        $financial = \App\Models\User::firstOrCreate(
            ['email' => 'finance@hostelflow.com'],
            [
                'name' => 'Financier',
                'password' => Hash::make('password123'),
                'status' => 'active'
            ]
        );

        $this->attachUserToHostel($financial, $hostel, 'financial');
    }

    /**
     * Attach user to hostel with role
     */
    private function attachUserToHostel($user, $hostel, $role)
    {
        if (!$user->hostels()
            ->where('hostels.id', $hostel->id)
            ->exists()) {

            $user->hostels()->attach(
                $hostel->id,
                [
                    'role' => $role,
                    'status' => 'active'
                ]
            );
        }
    }
}