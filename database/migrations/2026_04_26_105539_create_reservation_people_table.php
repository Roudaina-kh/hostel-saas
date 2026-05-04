<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_people', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservation_id')
                  ->constrained('reservations')
                  ->cascadeOnDelete();

            // Nullable : guest peut être supprimé
            $table->foreignId('guest_id')
                  ->nullable()
                  ->constrained('guests')
                  ->nullOnDelete();

            // Nom affiché, immuable même si guest modifié
            $table->string('display_name', 150);

            // Affectation polymorphe (logique, pas Eloquent morph)
            $table->enum('item_type', ['bed', 'room', 'tent_space']);
            $table->unsignedBigInteger('item_id');

            // Pricing — snapshot immutable
            $table->decimal('price_tnd', 10, 3);       // Valeur réelle interne
            $table->decimal('price_input', 10, 3);      // Valeur saisie
            $table->string('currency', 10);             // TND / EUR / USD
            $table->decimal('exchange_rate', 10, 4);    // Taux snapshot

            $table->boolean('is_checked_in')->default(false);

            $table->timestamps();

            // INDEX OBLIGATOIRES
            $table->index(['item_type', 'item_id'], 'idx_item');
            $table->index('reservation_id', 'idx_reservation');
            $table->index('guest_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_people');
    }
};