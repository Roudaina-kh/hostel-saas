<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_blocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hostel_id')
                ->constrained()
                ->cascadeOnDelete();

            // Relation polymorphique : room, bed, tent_space
            // Sécurité : permet de bloquer n'importe quel élément d'inventaire
            // sans créer une table par type
            $table->string('blockable_type');
            $table->unsignedBigInteger('blockable_id');

            $table->enum('block_type', ['maintenance', 'manual_block']);

            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->string('reason')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index('hostel_id');
            $table->index(['blockable_type', 'blockable_id']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_blocks');
    }
};