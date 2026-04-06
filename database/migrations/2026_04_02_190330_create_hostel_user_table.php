<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostel_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hostel_id')
                ->constrained('hostels')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('role', ['manager', 'staff', 'financial']);
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            $table->unique(['hostel_id', 'user_id']);
            $table->index('hostel_id');
            $table->index('user_id');
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_user');
    }
};