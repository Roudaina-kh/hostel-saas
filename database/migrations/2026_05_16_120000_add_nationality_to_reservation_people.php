<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservation_people', function (Blueprint $table) {
            // Nationalité du voyageur — colonne nullable, backward-compatible.
            $table->string('nationality', 100)
                  ->nullable()
                  ->after('display_name');

            // Index pour accélérer les agrégations GROUP BY pays du dashboard Analytics.
            $table->index('nationality', 'reservation_people_nationality_idx');
        });
    }

    public function down(): void
    {
        Schema::table('reservation_people', function (Blueprint $table) {
            $table->dropIndex('reservation_people_nationality_idx');
            $table->dropColumn('nationality');
        });
    }
};