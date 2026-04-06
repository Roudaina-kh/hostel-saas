<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Starting...\n";
if (!Schema::hasColumn('owners', 'status')) {
    Schema::table('owners', function (Blueprint $table) {
        $table->enum('status', ['active', 'inactive'])->default('active');
    });
    echo "Added status column to owners.\n";
}

DB::table('super_admins')->update(['password' => Hash::make('password'), 'is_active' => 1]);
DB::table('owners')->update(['password' => Hash::make('password'), 'status' => 'active']);
DB::table('users')->update(['password' => Hash::make('password'), 'status' => 'active']);
DB::table('hostel_user')->update(['status' => 'active']);

echo "Success.\n";
