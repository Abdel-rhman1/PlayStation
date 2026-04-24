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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('session_id')->constrained()->cascadeOnDelete();
            $table->decimal('device_price', 10, 2);
            $table->decimal('orders_total', 10, 2);
            $table->decimal('grand_total', 10, 2);
            $table->json('snapshot')->nullable(); // For futureproofing
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
