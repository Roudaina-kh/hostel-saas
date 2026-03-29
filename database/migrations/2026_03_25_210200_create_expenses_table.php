<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $blueprint->string('description');
            $blueprint->decimal('amount', 10, 2);
            $blueprint->string('category')->nullable();
            $blueprint->date('date');
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
