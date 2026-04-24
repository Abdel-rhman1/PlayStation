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
        // For performance, we index the combination of tenant_id and email
        // as this is the most common lookup in a multi-tenant SaaS.
        Schema::table('users', function (Blueprint $table) {
            $table->index(['tenant_id', 'email'], 'users_tenant_id_email_index');
        });

        // Ensure roles are efficiently searchable by tenant and name
        // (The unique constraint already exists, but we ensure it's explicitly tracked)
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasIndex('roles', 'roles_tenant_id_index')) {
                $table->index('tenant_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_tenant_id_email_index');
        });
    }
};
