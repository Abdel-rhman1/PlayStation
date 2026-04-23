<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('branch_id')->constrained()->cascadeOnDelete();
            $table->string('name')->index();
            $table->string('ip_address')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable()->comment('Pricing per hour for this device');
            $table->decimal('fixed_rate', 8, 2)->nullable()->comment('Fixed pricing per device session if applicable');
            $table->enum('status', ['ON', 'OFF', 'IN_USE'])->default('OFF')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
