<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hostel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ManagerSeeder extends Seeder
{
    public function run(): void
    {
        $hostel = Hostel::first();

        if (! $hostel) {
            $this->command->error('Aucun hostel trouvé. Créez d\'abord un hostel.');
            return;
        }

        if (User::where('email', 'manager@hostel-saas.com')->exists()) {
            $this->command->info('Manager existe déjà dans la table users.');
            return;
        }

        // Créer le User
        $user = User::create([
    'name'     => 'Manager Test',
    'email'    => 'manager@hostel-saas.com',
    'password' => Hash::make('Manager@2024!'), // ← Hash::make obligatoire
    'status'   => 'active',
]);
        // L'affecter au hostel via le pivot
        DB::table('hostel_user')->insert([
            'hostel_id'  => $hostel->id,
            'user_id'    => $user->id,
            'role'       => 'manager',
            'status'     => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Manager (User) créé avec succès.');
        $this->command->info('Email    : manager@hostel-saas.com');
        $this->command->info('Password : Manager@2024!');
        $this->command->info('Hostel   : ' . $hostel->name);
    }
}
