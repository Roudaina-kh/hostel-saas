<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // room_id n'est pas pertinent au niveau réservation :
            // chaque guest a sa propre affectation (bed / room / tent_space).
            // On le rend nullable pour ne pas bloquer le INSERT.
            $table->unsignedBigInteger('room_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->unsignedBigInteger('room_id')->nullable(false)->change();
        });
    }
};