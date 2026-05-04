<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Renomme amount → amount_tnd (compatible MariaDB 10.4)
        // CHANGE COLUMN = ancienne syntaxe compatible toutes versions
        if (Schema::hasColumn('payments', 'amount')) {
            DB::statement('ALTER TABLE `payments` CHANGE `amount` `amount_tnd` DECIMAL(10,3) NOT NULL DEFAULT 0');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('payments', 'amount_tnd')) {
            DB::statement('ALTER TABLE `payments` CHANGE `amount_tnd` `amount` DECIMAL(10,3) NOT NULL DEFAULT 0');
        }
    }
};