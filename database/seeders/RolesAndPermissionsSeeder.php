<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Core system permissions.
     * Format: 'name' => 'Human readable label'
     */
    private array $permissions = [
        'devices.manage'    => 'Manage Devices',
        'devices.view'      => 'View Devices',
        'sessions.manage'   => 'Start / Stop Sessions',
        'orders.view'       => 'View Orders',
        'orders.export'     => 'Export Orders',
        'expenses.manage'   => 'Manage Expenses',
        'expenses.view'     => 'View Expenses',
        'products.manage'   => 'Manage Products',
        'pos.access'        => 'Access POS',
        'reports.view'      => 'View Reports',
        'settings.manage'   => 'Manage Settings',
        'users.manage'      => 'Manage Users & Roles',
    ];

    public function run(): void
    {
        // Create all global permissions
        foreach ($this->permissions as $name => $label) {
            Permission::firstOrCreate(['name' => $name], ['label' => $label]);
        }

        $allPermissions     = Permission::all();
        $employeePermissions = Permission::whereIn('name', [
            'devices.view',
            'sessions.manage',
            'orders.view',
            'expenses.view',
            'products.manage',
            'pos.access',
            'reports.view',
        ])->get();

        // Create owner & employee roles for each tenant
        foreach (Tenant::all() as $tenant) {
            // Owner — gets all permissions (also bypassed by Gate::before)
            $owner = Role::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => 'owner'],
                ['description' => 'Full access to everything']
            );
            $owner->permissions()->sync($allPermissions->pluck('id'));

            // Employee — gets a safe subset of permissions
            $employee = Role::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => 'employee'],
                ['description' => 'Standard staff access']
            );
            $employee->permissions()->sync($employeePermissions->pluck('id'));
        }
    }
}
