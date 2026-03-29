<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hostel_requests', function (Blueprint $table) {
            $table->id();
            $table->string('hostel_name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('country')->default('Tunisie');
            $table->string('city');
            $table->string('phone');
            $table->string('skype_id')->nullable();
            $table->string('channel_manager')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_requests');
    }
};
