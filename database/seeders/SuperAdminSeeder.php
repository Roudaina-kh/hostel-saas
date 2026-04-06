<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Évite la duplication si le seeder est relancé
        if (SuperAdmin::where('email', 'superadmin@hostel-saas.com')->exists()) {
            $this->command->info('Super Admin existe déjà.');
            return;
        }

        SuperAdmin::create([
            'name'      => 'Super Admin',
            'email'     => 'superadmin@hostel-saas.com',
            'password'  => 'SuperAdmin@2024!', // Model hashed cast will handle hashing
            'phone'     => null,
            'is_active' => true,
        ]);

        $this->command->info('✅ Super Admin créé avec succès.');
        $this->command->info('Email    : superadmin@hostel-saas.com');
        $this->command->info('Password : SuperAdmin@2024!');
    }
}