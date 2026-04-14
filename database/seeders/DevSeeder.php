<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ────────────────────────────────────────
        \App\Models\SuperAdmin::firstOrCreate(
            ['email' => 'admin@hostel-saas.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('password123')]
        );

        // ── Propriétaire ───────────────────────────────────────
        $owner = \App\Models\Owner::firstOrCreate(
            ['email' => 'roudaina@gmail.com'],
            ['name' => 'Roudaina', 'password' => Hash::make('password123'), 'status' => 'active']
        );

        // ── Hostel ─────────────────────────────────────────────
        $hostel = \App\Models\Hostel::firstOrCreate(
            ['owner_id' => $owner->id, 'name' => 'Hostel Rou'],
            ['status' => 'active']
        );

        // ── Manager ────────────────────────────────────────────
        $manager = \App\Models\User::firstOrCreate(
            ['email' => 'manager@hostel-saas.com'],
            ['name' => 'Manager Test', 'password' => Hash::make('password123'), 'status' => 'active']
        );
        if (!$manager->hostels()->where('hostels.id', $hostel->id)->exists()) {
            $manager->hostels()->attach($hostel->id, ['role' => 'manager', 'status' => 'active']);
        }

        // ── Staff ──────────────────────────────────────────────
        $staff = \App\Models\User::firstOrCreate(
            ['email' => 'staff@hostel-saas.com'],
            ['name' => 'Staff Test', 'password' => Hash::make('password123'), 'status' => 'active']
        );
        if (!$staff->hostels()->where('hostels.id', $hostel->id)->exists()) {
            $staff->hostels()->attach($hostel->id, ['role' => 'staff', 'status' => 'active']);
        }

        // ── Financier ──────────────────────────────────────────
        $financial = \App\Models\User::firstOrCreate(
            ['email' => 'financial@hostel-saas.com'],
            ['name' => 'Financier Test', 'password' => Hash::make('password123'), 'status' => 'active']
        );
        if (!$financial->hostels()->where('hostels.id', $hostel->id)->exists()) {
            $financial->hostels()->attach($hostel->id, ['role' => 'financial', 'status' => 'active']);
        }
    }
}