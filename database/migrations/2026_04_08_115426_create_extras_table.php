<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hostel_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            // stock_mode détermine la logique de gestion du stock :
            // unlimited = pas de stock réel (ex: breakfast)
            // consumable = stock décrémentable (ex: bouteille d'eau)
            // rentable = ressource louable (ex: vélo)
            $table->enum('stock_mode', ['unlimited', 'consumable', 'rentable'])
                ->default('unlimited');

            $table->unsignedInteger('stock_quantity')->nullable();
            $table->unsignedInteger('stock_alert_threshold')->nullable();

            $table->boolean('is_enabled')->default(true);

            $table->timestamps();

            $table->unique(['hostel_id', 'name']);
            $table->index('hostel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extras');
    }
};