<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hostels', function (Blueprint $table) {
            if (!Schema::hasColumn('hostels', 'default_currency')) {
                $table->string('default_currency', 10)->default('TND')->after('country');
            }
            if (!Schema::hasColumn('hostels', 'timezone')) {
                $table->string('timezone', 60)->default('Africa/Tunis')->after('default_currency');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hostels', function (Blueprint $table) {
            if (Schema::hasColumn('hostels', 'timezone')) {
                $table->dropColumn('timezone');
            }
            if (Schema::hasColumn('hostels', 'default_currency')) {
                $table->dropColumn('default_currency');
            }
        });
    }
};