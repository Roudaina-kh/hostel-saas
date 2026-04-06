<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Supprimer la table managers si elle existe (phantom table)
        Schema::dropIfExists('managers');

        // 2. Nettoyer la table users (identité uniquement)
        Schema::table('users', function (Blueprint $table) {
            // Supprimer les colonnes de permissions si elles existent
            $cols = [
                'can_manage_rooms', 'can_manage_reservations', 'can_manage_team',
                'can_view_financials', 'can_manage_pricing', 'can_manage_taxes'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        // 3. Nettoyer la table hostel_user (pivot uniquement)
        Schema::table('hostel_user', function (Blueprint $table) {
            // Supprimer owner_id s'il existe (redondant car déductible du hostel)
            if (Schema::hasColumn('hostel_user', 'owner_id')) {
                $table->dropColumn('owner_id');
            }
        });
    }

    public function down(): void
    {
        // Pas de retour en arrière spécifique pour cette migration de nettoyage structurel
    }
};
