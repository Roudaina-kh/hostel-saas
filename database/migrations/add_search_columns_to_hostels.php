<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('hostels', function (Blueprint $table) {
            if (!Schema::hasColumn('hostels', 'region_id')) {
                $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete()->after('owner_id');
            }
            if (!Schema::hasColumn('hostels', 'type')) {
                $table->enum('type', ['hostel', 'camping', 'mixed'])->default('hostel')->after('region_id');
            }
            if (!Schema::hasColumn('hostels', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('type');
            }
            if (!Schema::hasColumn('hostels', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('hostels', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0)->after('longitude');
            }
            if (!Schema::hasColumn('hostels', 'total_reviews')) {
                $table->unsignedInteger('total_reviews')->default(0)->after('rating');
            }
            if (!Schema::hasColumn('hostels', 'description')) {
                $table->text('description')->nullable()->after('total_reviews');
            }
            if (!Schema::hasColumn('hostels', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('description');
            }

            $table->index('region_id');
            $table->index('type');
            $table->index('rating');
            $table->index('is_active');
        });

        // Index pour la disponibilité (critique pour performance)
        Schema::table('reservations', function (Blueprint $table) {
            try {
                $table->index(['hostel_id', 'start_date', 'end_date', 'status'], 'idx_res_avail');
            } catch (\Exception $e) {
                // Index existe déjà
            }
        });
    }

    public function down(): void
    {
        Schema::table('hostels', function (Blueprint $table) {
            $table->dropColumn(['region_id', 'type', 'latitude', 'longitude', 'rating', 'total_reviews', 'description', 'cover_image']);
        });
    }
};