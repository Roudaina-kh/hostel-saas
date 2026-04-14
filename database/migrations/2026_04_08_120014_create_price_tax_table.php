<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table pivot entre prices et taxes
        // Permet d'associer plusieurs taxes à un même prix
        // Ex: un prix de bed peut avoir TVA 13% + taxe séjour
        Schema::create('price_tax', function (Blueprint $table) {
            $table->id();

            $table->foreignId('price_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('tax_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['price_id', 'tax_id']);
            $table->index('price_id');
            $table->index('tax_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_tax');
    }
};