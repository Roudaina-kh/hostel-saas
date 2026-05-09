<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('regions')) {
            // Table existe déjà — on vérifie juste les colonnes manquantes
            Schema::table('regions', function (Blueprint $table) {
                if (!Schema::hasColumn('regions', 'hostels_count')) {
                    $table->unsignedInteger('hostels_count')->default(0);
                }
                if (!Schema::hasColumn('regions', 'slug')) {
                    $table->string('slug', 100)->unique();
                }
                if (!Schema::hasColumn('regions', 'type')) {
                    $table->enum('type', ['gouvernorat', 'ville', 'zone'])->default('gouvernorat');
                }
                if (!Schema::hasColumn('regions', 'parent_id')) {
                    $table->foreignId('parent_id')->nullable()->constrained('regions')->nullOnDelete();
                }
                if (!Schema::hasColumn('regions', 'latitude')) {
                    $table->decimal('latitude', 10, 8)->nullable();
                }
                if (!Schema::hasColumn('regions', 'longitude')) {
                    $table->decimal('longitude', 11, 8)->nullable();
                }
            });
            return;
        }

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->enum('type', ['gouvernorat', 'ville', 'zone']);
            $table->foreignId('parent_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->unsignedInteger('hostels_count')->default(0);
            $table->timestamps();

            $table->index('parent_id');
            $table->index('type');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};