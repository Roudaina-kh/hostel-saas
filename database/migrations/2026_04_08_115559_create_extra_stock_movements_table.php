<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('extra_stock_movements')) {
            return;
        }

        Schema::create('extra_stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hostel_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('extra_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('movement_type', [
                'initial',
                'purchase',
                'adjustment_in',
                'adjustment_out',
                'damage',
                'loss',
                'return',
            ]);

            $table->unsignedInteger('quantity');
            $table->text('note')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index('hostel_id');
            $table->index('extra_id');
            $table->index('movement_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extra_stock_movements');
    }
};