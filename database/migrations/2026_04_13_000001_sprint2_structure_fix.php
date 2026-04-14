<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── ROOMS ───────────────────────────────────────────────
        Schema::table('rooms', function (Blueprint $table) {
            // Supprimer min_capacity (hors spec)
            if (Schema::hasColumn('rooms', 'min_capacity')) {
                $table->dropColumn('min_capacity');
            }

            // Ajouter UNIQUE(hostel_id, name)
            // ⚠️ Outil de sécurité : unicité du nom par hostel
            $table->unique(['hostel_id', 'name'], 'rooms_hostel_id_name_unique');

            // Indexes de performance
            $table->index(['hostel_id', 'type'], 'rooms_hostel_id_type_index');
        });

        // ─── BEDS ────────────────────────────────────────────────
        Schema::table('beds', function (Blueprint $table) {
            // Ajouter UNIQUE(room_id, name)
            // ⚠️ Outil de sécurité : unicité du nom par room
            $table->unique(['room_id', 'name'], 'beds_room_id_name_unique');

            // Index de performance
            $table->index('room_id', 'beds_room_id_index');
        });

        // ─── TENT SPACES ─────────────────────────────────────────
        Schema::table('tent_spaces', function (Blueprint $table) {
            // Supprimer status (hors spec)
            if (Schema::hasColumn('tent_spaces', 'status')) {
                $table->dropColumn('status');
            }

            // Renommer is_active → is_enabled
            if (Schema::hasColumn('tent_spaces', 'is_active') &&
                ! Schema::hasColumn('tent_spaces', 'is_enabled')) {
                $table->renameColumn('is_active', 'is_enabled');
            }

            // Rendre max_tents et max_persons nullable sans défaut
            $table->unsignedSmallInteger('max_tents')->nullable()->default(null)->change();
            $table->unsignedSmallInteger('max_persons')->nullable()->default(null)->change();

            // ⚠️ Outil de sécurité : unicité du nom par hostel
            $table->unique(['hostel_id', 'name'], 'tent_spaces_hostel_id_name_unique');

            // Index de performance
            $table->index('hostel_id', 'tent_spaces_hostel_id_index');
        });

        // ─── PRICES ──────────────────────────────────────────────
        Schema::table('prices', function (Blueprint $table) {
            // Supprimer is_active (hors spec)
            if (Schema::hasColumn('prices', 'is_active')) {
                $table->dropColumn('is_active');
            }

            // Index de performance
            $table->index(['hostel_id', 'pricing_mode'], 'prices_hostel_id_pricing_mode_index');
        });

        // ─── INVENTORY BLOCKS ────────────────────────────────────
        Schema::table('inventory_blocks', function (Blueprint $table) {
            $table->index(['hostel_id', 'block_type'], 'inventory_blocks_hostel_id_block_type_index');
        });

        // ─── EXTRAS ──────────────────────────────────────────────
        Schema::table('extras', function (Blueprint $table) {
            $table->index(['hostel_id', 'stock_mode'], 'extras_hostel_id_stock_mode_index');
        });

        // ─── EXTRA STOCK MOVEMENTS ───────────────────────────────
        Schema::table('extra_stock_movements', function (Blueprint $table) {
            $table->index(['extra_id', 'created_at'], 'esm_extra_id_created_at_index');
        });

        // ─── TAXES ───────────────────────────────────────────────
        Schema::table('taxes', function (Blueprint $table) {
            $table->index(['hostel_id', 'type'], 'taxes_hostel_id_type_index');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropUnique('rooms_hostel_id_name_unique');
            $table->dropIndex('rooms_hostel_id_type_index');
            $table->unsignedTinyInteger('min_capacity')->default(1)->after('type');
        });

        Schema::table('beds', function (Blueprint $table) {
            $table->dropUnique('beds_room_id_name_unique');
            $table->dropIndex('beds_room_id_index');
        });

        Schema::table('tent_spaces', function (Blueprint $table) {
            $table->dropUnique('tent_spaces_hostel_id_name_unique');
            $table->dropIndex('tent_spaces_hostel_id_index');
        });

        Schema::table('prices', function (Blueprint $table) {
            $table->dropIndex('prices_hostel_id_pricing_mode_index');
            $table->boolean('is_active')->default(false)->after('valid_to');
        });

        Schema::table('inventory_blocks', function (Blueprint $table) {
            $table->dropIndex('inventory_blocks_hostel_id_block_type_index');
        });

        Schema::table('extras', function (Blueprint $table) {
            $table->dropIndex('extras_hostel_id_stock_mode_index');
        });

        Schema::table('extra_stock_movements', function (Blueprint $table) {
            $table->dropIndex('esm_extra_id_created_at_index');
        });

        Schema::table('taxes', function (Blueprint $table) {
            $table->dropIndex('taxes_hostel_id_type_index');
        });
    }
};