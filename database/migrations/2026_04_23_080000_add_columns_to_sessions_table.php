<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('sessions', 'tenant_id')) {
                $table->foreignId('tenant_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('sessions', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('tenant_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('sessions', 'total_price')) {
                $table->decimal('total_price', 12, 2)->default(0)->after('cost');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('total_price');
        });
    }
};
