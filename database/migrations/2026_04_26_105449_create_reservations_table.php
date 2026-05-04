<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hostel_id')
                  ->constrained('hostels')
                  ->cascadeOnDelete();

            $table->foreignId('main_guest_id')
                  ->constrained('guests')
                  ->restrictOnDelete();

            $table->date('start_date');
            $table->date('end_date');
            $table->integer('nights');

            $table->integer('total_guests');

            $table->enum('status', [
                'pending',
                'confirmed',
                'checked_in',
                'checked_out',
                'cancelled',
            ])->default('pending');

            $table->string('source', 100)->nullable();

            $table->decimal('total_price_tnd', 10, 3)->default(0);
            $table->decimal('total_price_eur', 10, 3)->nullable();
            $table->decimal('total_price_usd', 10, 3)->nullable();

            $table->text('notes')->nullable();

            // Qui a créé la réservation
            $table->string('created_by', 150);
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->restrictOnDelete();

            $table->timestamps();

            // Index critiques
            $table->index(['hostel_id', 'start_date', 'end_date']);
            $table->index(['hostel_id', 'status']);
            $table->index('main_guest_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};