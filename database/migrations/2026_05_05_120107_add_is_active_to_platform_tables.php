<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Ajoute is_active aux users (managers/staff/financial)
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('status');
        });

        // Ajoute is_active aux owners
        if (!Schema::hasColumn('owners', 'is_active')) {
            Schema::table('owners', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('email');
            });
        }

        // Ajoute is_active aux hostels
        if (!Schema::hasColumn('hostels', 'is_active')) {
            Schema::table('hostels', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('name');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
        Schema::table('owners', function (Blueprint $table) {
            if (Schema::hasColumn('owners', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
        Schema::table('hostels', function (Blueprint $table) {
            if (Schema::hasColumn('hostels', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};