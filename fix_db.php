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
use Illuminate\Support\Facades\DB;

$password = 'password'; // Use plain text, model cast 'hashed' will hash it once!

// 1. Super Admin
$sa = SuperAdmin::firstOrCreate(
    ['email' => 'superadmin@hostel-saas.com'],
    ['name' => 'Super Admin', 'password' => $password, 'is_active' => true]
);
// Force update if it already exists
$sa->password = $password;
$sa->save();
echo "SuperAdmin: {$sa->email} / {$password}\n";

// 2. Owner
$owner = Owner::firstOrCreate(
    ['email' => 'roudaina@gmail.com'],
    ['name' => 'Owner Test', 'password' => $password, 'status' => 'active']
);
$owner->password = $password;
$owner->save();
echo "Owner: {$owner->email} / {$password}\n";

// Ensure at least one Hostel exists
$hostel = Hostel::firstOrCreate(
    ['name' => 'Hostel Flow Central'],
    ['owner_id' => $owner->id, 'address' => '123 Main St', 'city' => 'Paris', 'country' => 'France', 'status' => 'active']
);

// 3. Manager
$manager = User::firstOrCreate(
    ['email' => 'manager@hostel-saas.com'],
    ['name' => 'Manager User', 'password' => $password, 'status' => 'active']
);
$manager->password = $password;
$manager->save();
// Update pivot without detaching
DB::table('hostel_user')->updateOrInsert(
    ['hostel_id' => $hostel->id, 'user_id' => $manager->id],
    ['role' => 'manager', 'status' => 'active', 'updated_at' => now()]
);
echo "Manager: {$manager->email} / {$password}\n";

// 4. Staff
$staff = User::firstOrCreate(
    ['email' => 'staff@hostel-saas.com'],
    ['name' => 'Staff User', 'password' => $password, 'status' => 'active']
);
$staff->password = $password;
$staff->save();
DB::table('hostel_user')->updateOrInsert(
    ['hostel_id' => $hostel->id, 'user_id' => $staff->id],
    ['role' => 'staff', 'status' => 'active', 'updated_at' => now()]
);
echo "Staff: {$staff->email} / {$password}\n";

echo "Done seeding.\n";

