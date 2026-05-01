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
        Schema::create('device_player_pricing', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('device_id')->constrained()->cascadeOnDelete();
            $table->integer('player_count')->comment('2 or 4');
            $table->decimal('price_per_hour', 8, 2);
            $table->timestamps();

            $table->unique(['device_id', 'player_count']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_player_pricing');
    }
};
