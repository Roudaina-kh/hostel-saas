<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beds', function (Blueprint $table) {

            // Supprimer hostel_id si elle existe
            // (redondant car récupérable via room → hostel)
            if (Schema::hasColumn('beds', 'hostel_id')) {
                // Supprimer la FK d'abord
                $table->dropForeign(['hostel_id']);
                $table->dropColumn('hostel_id');
            }

            // Supprimer maintenance si elle existe
            if (Schema::hasColumn('beds', 'maintenance')) {
                $table->dropColumn('maintenance');
            }

            // Supprimer status si elle existe
            if (Schema::hasColumn('beds', 'status')) {
                $table->dropColumn('status');
            }

            // Ajouter is_enabled si elle n'existe pas
            if (! Schema::hasColumn('beds', 'is_enabled')) {
                $table->boolean('is_enabled')->default(true)->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('beds', function (Blueprint $table) {
            if (Schema::hasColumn('beds', 'is_enabled')) {
                $table->dropColumn('is_enabled');
            }
            $table->foreignId('hostel_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('maintenance')->default(false);
            $table->string('status')->nullable();
        });
    }
};