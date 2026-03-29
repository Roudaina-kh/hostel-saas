<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $blueprint->decimal('amount', 10, 2);
            $blueprint->string('payment_method')->default('cash');
            $blueprint->enum('status', ['pending', 'paid', 'refunded'])->default('paid');
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
