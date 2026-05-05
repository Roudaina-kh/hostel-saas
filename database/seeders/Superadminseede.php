<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Crée le super admin par défaut
        // ⚠️ Change le mot de passe après la première connexion !
        SuperAdmin::firstOrCreate(
            ['email' => 'superadmin@hostelflow.com'],
            [
                'name'      => 'Super Admin',
                'email'     => 'superadmin@hostelflow.com',
                'password'  => Hash::make('Admin@2026!'),  // 🔒 Mot de passe fort
                'phone'     => null,
                'is_active' => true,
            ]
        );

        $this->command->info('✅ Super Admin créé :');
        $this->command->info('   Email    : superadmin@hostelflow.com');
        $this->command->info('   Password : Admin@2026!');
        $this->command->warn('   ⚠️  Changez ce mot de passe après la première connexion !');
    }
}