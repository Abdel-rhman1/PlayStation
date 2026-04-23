<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE device_logs MODIFY COLUMN action ENUM('ON', 'OFF', 'START_SESSION', 'STOP_SESSION') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE device_logs MODIFY COLUMN action ENUM('ON', 'OFF') NOT NULL");
    }
};
