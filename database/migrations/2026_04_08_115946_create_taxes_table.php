<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hostel_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');

            // Type de calcul de la taxe :
            // percentage = TVA en % (ex: 13%)
            // fixed_amount = montant fixe
            // fixed_per_night = montant fixe par nuit
            // fixed_per_person_per_night = montant par personne et par nuit
            $table->enum('type', [
                'percentage',
                'fixed_amount',
                'fixed_per_night',
                'fixed_per_person_per_night',
            ]);

            $table->decimal('amount', 10, 3)->default(0.000);
            $table->boolean('is_enabled')->default(true);
            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(['hostel_id', 'name']);
            $table->index('hostel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};