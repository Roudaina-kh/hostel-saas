<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();

            $table->string('first_name', 100);
            $table->string('last_name', 100);

            $table->string('identity_card', 100)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 50)->nullable();

            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnDelete();

            $table->enum('gender', ['male', 'female']);

            $table->timestamps();

            // Index pour la recherche rapide
            $table->index(['first_name', 'last_name']);
            $table->index('identity_card');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};