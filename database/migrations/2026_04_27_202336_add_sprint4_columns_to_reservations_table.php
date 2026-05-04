<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {

            // Lister les colonnes existantes pour ne pas recréer ce qui existe
            $existing = Schema::getColumnListing('reservations');

            if (!in_array('main_guest_id', $existing)) {
                $table->unsignedBigInteger('main_guest_id')->nullable();
                $table->foreign('main_guest_id')
                      ->references('id')->on('guests')
                      ->nullOnDelete();
            }

            if (!in_array('nights', $existing)) {
                $table->integer('nights')->default(1);
            }

            if (!in_array('total_guests', $existing)) {
                $table->integer('total_guests')->default(1);
            }

            if (!in_array('source', $existing)) {
                $table->string('source', 100)->nullable();
            }

            if (!in_array('total_price_tnd', $existing)) {
                $table->decimal('total_price_tnd', 10, 3)->default(0);
            }

            if (!in_array('total_price_eur', $existing)) {
                $table->decimal('total_price_eur', 10, 3)->nullable();
            }

            if (!in_array('total_price_usd', $existing)) {
                $table->decimal('total_price_usd', 10, 3)->nullable();
            }

            if (!in_array('notes', $existing)) {
                $table->text('notes')->nullable();
            }

            if (!in_array('created_by', $existing)) {
                $table->string('created_by', 150)->nullable();
            }

            if (!in_array('user_id', $existing)) {
                $table->foreignId('user_id')
                      ->nullable()
                      ->constrained('users')
                      ->nullOnDelete();
            }

        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Supprimer les foreign keys avant les colonnes
            try { $table->dropForeign(['main_guest_id']); } catch (\Exception $e) {}
            try { $table->dropForeign(['user_id']); } catch (\Exception $e) {}

            $toDrop = [
                'main_guest_id', 'nights', 'total_guests', 'source',
                'total_price_tnd', 'total_price_eur', 'total_price_usd',
                'notes', 'created_by', 'user_id',
            ];

            $existing = Schema::getColumnListing('reservations');
            $table->dropColumn(array_intersect($toDrop, $existing));
        });
    }
};