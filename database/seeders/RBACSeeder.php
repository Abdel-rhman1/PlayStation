<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class RBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Baseline Permissions (Global)
        $permissions = [
            ['name' => 'devices.manage', 'label' => 'Manage Devices'],
            ['name' => 'sessions.manage', 'label' => 'Manage Sessions'],
            ['name' => 'pos.orders', 'label' => 'Manage POS Orders'],
            ['name' => 'finance.view', 'label' => 'View Financials'],
            ['name' => 'reports.view', 'label' => 'View Reports'],
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p['name']], $p);
        }

        // 2. Create Roles for each Tenant
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            // Owner Role - All Permissions
            $owner = Role::firstOrCreate([
                'tenant_id' => $tenant->id,
                'name' => 'owner'
            ], [
                'description' => 'Tenant Owner with full access'
            ]);
            $owner->permissions()->sync(Permission::all());

            // Employee Role - Limited Permissions
            $employee = Role::firstOrCreate([
                'tenant_id' => $tenant->id,
                'name' => 'employee'
            ], [
                'description' => 'Regular employee access'
            ]);
            
            $employeePermissions = Permission::whereIn('name', [
                'devices.manage',
                'sessions.manage',
                'pos.orders'
            ])->get();
            
            $employee->permissions()->sync($employeePermissions);
        }
    }
}
