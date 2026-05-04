<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();

            // hostel_id ajouté pour respecter l'isolation multi-hostel du projet
            $table->foreignId('hostel_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('currency', 10);

            $table->decimal('buy_rate_to_tnd', 10, 4);
            $table->decimal('sell_rate_to_tnd', 10, 4);

            // created_by : utilisateur ayant saisi le taux
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('currency');
            $table->index(['currency', 'created_at']);
            $table->index('hostel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};