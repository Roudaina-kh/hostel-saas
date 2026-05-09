<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE contact_requests
            MODIFY COLUMN status
            ENUM('new','read','replied','confirmed','cancelled')
            NOT NULL DEFAULT 'new'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE contact_requests
            MODIFY COLUMN status
            ENUM('new','read','replied')
            NOT NULL DEFAULT 'new'");
    }
};