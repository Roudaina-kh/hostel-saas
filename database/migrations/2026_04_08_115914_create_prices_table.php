<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hostel_id')
                ->constrained()
                ->cascadeOnDelete();

            // Relation polymorphique : room, tent_space, extra
            // Une seule table de prix pour tous les types d'éléments
            $table->string('priceable_type');
            $table->unsignedBigInteger('priceable_id');

            // pricing_mode doit être cohérent avec le type réel :
            // room private → per_room
            // room dormitory → per_bed
            // tent_space → per_person
            // extra → per_unit, per_night, per_person, per_person_per_night
            $table->enum('pricing_mode', [
                'per_room',
                'per_bed',
                'per_person',
                'per_unit',
                'per_night',
                'per_person_per_night',
            ]);

            // price_ht et price_ttc sont tous les deux stockés
            // pour éviter de recalculer le TTC à chaque lecture
            $table->decimal('price_ht', 10, 3)->default(0.000);
            $table->decimal('price_ttc', 10, 3)->default(0.000);

            $table->date('valid_from');
            $table->date('valid_to')->nullable();

            $table->timestamps();

            $table->index('hostel_id');
            $table->index(['priceable_type', 'priceable_id']);
            $table->index(['valid_from', 'valid_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};