<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {

            // Supprimer status si elle existe (ancienne logique)
            if (Schema::hasColumn('rooms', 'status')) {
                $table->dropColumn('status');
            }

            // Ajouter is_enabled si elle n'existe pas
            if (! Schema::hasColumn('rooms', 'is_enabled')) {
                $table->boolean('is_enabled')->default(true)->after('max_capacity');
            }

            // Ajouter description si elle n'existe pas
            if (! Schema::hasColumn('rooms', 'description')) {
                $table->text('description')->nullable()->after('is_enabled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'is_enabled')) {
                $table->dropColumn('is_enabled');
            }
            if (Schema::hasColumn('rooms', 'description')) {
                $table->dropColumn('description');
            }
            $table->string('status')->nullable();
        });
    }
};