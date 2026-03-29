<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $blueprint->foreignId('room_id')->constrained()->onDelete('cascade');
            $blueprint->string('guest_name');
            $blueprint->date('start_date');
            $blueprint->date('end_date');
            $blueprint->enum('status', ['pending', 'confirmed', 'checked-in', 'checked-out', 'cancelled'])->default('pending');
            $blueprint->decimal('total_price', 10, 2)->default(0);
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
