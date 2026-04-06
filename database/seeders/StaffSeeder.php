<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère le premier hostel disponible
        $hostel = DB::table('hostels')->first();

        if (! $hostel) {
            $this->command->error('❌ Aucun hostel trouvé. Connecte-toi en tant que owner et crée un hostel d\'abord.');
            return;
        }

        // ── Manager ──────────────────────────────────────
        $manager = User::updateOrCreate(
    ['email' => 'manager@hostel-saas.com'],
    [
        'name'     => 'Manager Test',
        'password' => 'Manager@2024!', // Model hashed cast will handle hashing
        'status'   => 'active',
    ]
);

        DB::table('hostel_user')->insertOrIgnore([
            'hostel_id'  => $hostel->id,
            'user_id'    => $manager->id,
            'role'       => 'manager',
            'status'     => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── Financier ─────────────────────────────────────
    $financier = User::updateOrCreate(
    ['email' => 'finance@hostel-flow.com'],
    [
        'name'     => 'Responsable Financier',
        'password' => 'Finance@2024!', // ← plain text
        'status'   => 'active',
    ]
);
        DB::table('hostel_user')->insertOrIgnore([
            'hostel_id'  => $hostel->id,
            'user_id'    => $financier->id,
            'role'       => 'financial',
            'status'     => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── Staff ─────────────────────────────────────────
        $staff = User::updateOrCreate(
    ['email' => 'staff@hostel-saas.com'],
    [
        'name'     => 'Staff Test',
        'password' => 'Staff@2024!', // Model hashed cast will handle hashing
        'status'   => 'active',
    ]
);

        DB::table('hostel_user')->insertOrIgnore([
            'hostel_id'  => $hostel->id,
            'user_id'    => $staff->id,
            'role'       => 'staff',
            'status'     => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Manager     : manager@hostel-saas.com / Manager@2024!');
        $this->command->info('✅ Financier   : finance@hostel-flow.com / Finance@2024!');
        $this->command->info('✅ Staff       : staff@hostel-saas.com / Staff@2024!');
        $this->command->info('   Hostel utilisé : ' . $hostel->name);
    }
}