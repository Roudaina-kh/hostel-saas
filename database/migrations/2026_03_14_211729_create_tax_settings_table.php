<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tax_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->constrained('hostels')->cascadeOnDelete()->unique();
            $table->boolean('taxes_enabled')->default(false);
            $table->decimal('vat_percentage', 5, 2)->default(0);
            $table->decimal('city_tax_per_night', 10, 3)->default(0);
            $table->decimal('per_person_tax_per_night', 10, 3)->default(0);
            $table->decimal('service_fee_percentage', 5, 2)->default(0);
            $table->boolean('extras_taxable')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_settings');
    }
};