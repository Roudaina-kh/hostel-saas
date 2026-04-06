<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$log = [];
try {
    if (!Schema::hasColumn('owners', 'status')) {
        Schema::table('owners', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        $log[] = "Added status column to owners.";
    }

    if (!Schema::hasColumn('users', 'status')) {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
        $log[] = "Added status column to users.";
    }

    DB::table('super_admins')->update([
        'password' => Hash::make('password'),
        'is_active' => 1
    ]);
    
    DB::table('owners')->update([
        'password' => Hash::make('password'),
        'status' => 'active'
    ]);

    DB::table('users')->update([
        'password' => Hash::make('password'),
        'status' => 'active'
    ]);

    DB::table('hostel_user')->update([
        'status' => 'active'
    ]);

    $log[] = "Success update.";
} catch (\Exception $e) {
    $log[] = "Error: " . $e->getMessage();
}

file_put_contents('fix_log.txt', implode("\n", $log));
