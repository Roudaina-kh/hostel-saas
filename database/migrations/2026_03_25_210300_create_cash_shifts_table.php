<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_shifts', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $blueprint->foreignId('user_id')->constrained()->onDelete('cascade'); // The staff member
            $blueprint->decimal('opening_balance', 10, 2);
            $blueprint->decimal('closing_balance', 10, 2)->nullable();
            $blueprint->timestamp('opened_at');
            $blueprint->timestamp('closed_at')->nullable();
            $blueprint->enum('status', ['open', 'closed'])->default('open');
            $blueprint->text('notes')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_shifts');
    }
};
