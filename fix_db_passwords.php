<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Fix super_admins
DB::table('super_admins')->update([
    'password' => Hash::make('password'),
    'is_active' => 1
]);
echo "SuperAdmins passwords reset.\n";

// Fix owners
if (!Schema::hasColumn('owners', 'status')) {
    Schema::table('owners', function (Blueprint $table) {
        $table->enum('status', ['active', 'inactive'])->default('active');
    });
    echo "Added status column to owners.\n";
}
DB::table('owners')->update([
    'password' => Hash::make('password'),
    'status' => 'active'
]);
echo "Owners passwords reset and status set to active.\n";

// Fix users
DB::table('users')->update([
    'password' => Hash::make('password'),
    'status' => 'active'
]);
echo "Users passwords reset and status set to active.\n";

// Fix hostel_user linking just in case
DB::table('hostel_user')->update([
    'status' => 'active'
]);
echo "Hostel_user pivot status set to active.\n";

echo "Done.";
