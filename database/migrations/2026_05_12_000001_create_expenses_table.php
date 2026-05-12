<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hostel_id')
                ->constrained()
                ->cascadeOnDelete();

            // Auteur côté manager/staff/financial (guard 'user')
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Auteur côté owner (guard 'owner')
            $table->foreignId('owner_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Dénormalisation pour affichage rapide : "Marie Dupont (Propriétaire)"
            $table->string('creator_label')->nullable();

            // Personne ayant réellement payé (texte libre)
            $table->string('payer_name');

            $table->string('category');                  // ex: 'maintenance'
            $table->string('label');                     // titre court
            $table->decimal('amount', 12, 3);
            $table->string('currency', 10)->default('TND');
            $table->date('expense_date');
            $table->text('note')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('hostel_id');
            $table->index('user_id');
            $table->index('owner_id');
            $table->index('category');
            $table->index('expense_date');
            $table->index(['hostel_id', 'expense_date']);   // Index composé pour reporting
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};