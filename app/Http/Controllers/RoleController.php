<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'has_full_branch_access' => 'boolean',
        ]);

        Role::create([
            'tenant_id' => auth()->user()->tenant_id,
            'name' => strtolower(trim($validated['name'])),
            'description' => $validated['description'],
            'has_full_branch_access' => $request->boolean('has_full_branch_access'),
        ]);

        return redirect()->route('roles.index')
            ->with('success', __('notifications.role_created'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'has_full_branch_access' => 'boolean',
        ]);

        $role->update([
            'has_full_branch_access' => $request->boolean('has_full_branch_access'),
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('roles.index')
            ->with('success', __('notifications.role_updated'));
    }
}
