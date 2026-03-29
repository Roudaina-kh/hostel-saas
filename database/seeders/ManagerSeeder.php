<?php

namespace Database\Seeders;

use App\Models\Manager;
use App\Models\Hostel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    public function run(): void
    {
        $hostel = Hostel::first();

        if (! $hostel) {
            $this->command->error('Aucun hostel trouvé. Créez d\'abord un hostel.');
            return;
        }

        if (Manager::where('email', 'manager@hostel-saas.com')->exists()) {
            $this->command->info('Manager existe déjà.');
            return;
        }

        Manager::create([
            'hostel_id'               => $hostel->id,
            'owner_id'                => $hostel->owner_id,
            'name'                    => 'Manager Test',
            'email'                   => 'manager@hostel-saas.com',
            'password'                => Hash::make('Manager@2024!'),
            'is_active'               => true,
            'can_manage_rooms'        => true,
            'can_manage_reservations' => true,
            'can_manage_team'         => true,
            'can_view_financials'     => true,
            'can_manage_pricing'      => false,
            'can_manage_taxes'        => false,
        ]);

        $this->command->info('✅ Manager créé avec succès.');
        $this->command->info('Email    : manager@hostel-saas.com');
        $this->command->info('Password : Manager@2024!');
        $this->command->info('Hostel   : ' . $hostel->name);
    }
}
