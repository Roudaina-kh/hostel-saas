<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Owner;
use App\Models\SuperAdmin;
use App\Models\Hostel;
use Illuminate\Support\Facades\Hash;

$password = Hash::make('FinanceFlow2026');

// 1. Super Admin
$sa = SuperAdmin::firstOrCreate(
    ['email' => 'admin@hostel-flow.com'],
    ['name' => 'Super Admin', 'password' => $password, 'is_active' => true]
);
$sa->password = $password;
$sa->save();
echo "SuperAdmin: {$sa->email} / FinanceFlow2026\n";

// 2. Owner
$owner = Owner::firstOrCreate(
    ['email' => 'owner@hostel-flow.com'],
    ['name' => 'Hostel Owner', 'password' => $password, 'status' => 'active']
);
$owner->password = $password;
$owner->save();
echo "Owner: {$owner->email} / FinanceFlow2026\n";

// Ensure at least one Hostel exists
$hostel = Hostel::firstOrCreate(
    ['name' => 'Hostel Flow Central'],
    ['owner_id' => $owner->id, 'address' => '123 Main St', 'city' => 'Paris', 'country' => 'France', 'status' => 'active']
);

// 3. Manager
$manager = User::firstOrCreate(
    ['email' => 'manager@hostel-flow.com'],
    ['name' => 'Manager User', 'password' => $password, 'status' => 'active']
);
$manager->password = $password;
$manager->save();
$manager->hostels()->syncWithoutDetaching([$hostel->id => ['role' => 'manager', 'status' => 'active']]);
echo "Manager: {$manager->email} / FinanceFlow2026\n";

// 4. Financial
$finance = User::firstOrCreate(
    ['email' => 'finance@hostel-flow.com'],
    ['name' => 'Financial User', 'password' => $password, 'status' => 'active']
);
$finance->password = $password;
$finance->save();
$finance->hostels()->syncWithoutDetaching([$hostel->id => ['role' => 'financial', 'status' => 'active']]);
echo "Financial: {$finance->email} / FinanceFlow2026\n";

echo "Done seeding.\n";
