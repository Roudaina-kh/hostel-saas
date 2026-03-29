<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajouter phone seulement si elle n'existe pas
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            // Ajouter status seulement si elle n'existe pas
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])
                      ->default('active')->after('password');
            }
        });

        // Migrer is_active → status si is_active existe encore
        if (Schema::hasColumn('users', 'is_active')) {
            DB::statement("UPDATE users SET status = IF(is_active = 1, 'active', 'inactive')");
        }

        Schema::table('users', function (Blueprint $table) {
            // Supprimer hostel_id si elle existe
            if (Schema::hasColumn('users', 'hostel_id')) {
                $table->dropForeign(['hostel_id']);
                $table->dropColumn('hostel_id');
            }
            // Supprimer role si elle existe
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            // Supprimer is_active si elle existe
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('hostel_id')->nullable();
            $table->string('role')->nullable();
            $table->boolean('is_active')->default(true);
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};