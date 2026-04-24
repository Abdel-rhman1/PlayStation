<?php

namespace App\Core\Auth\Traits;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPermissions
{
    /**
     * Users belong to a specific role.
     */
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Users can access specific branches.
     */
    public function branches(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Branch::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string ...$roleNames): bool
    {
        return in_array($this->role?->name, $roleNames);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permissionName): bool
    {
        if (!$this->role) return false;

        return $this->role->permissions()
            ->where('name', $permissionName)
            ->exists();
    }

    /**
     * Alias for hasPermission (to work with Gate/can).
     */
    public function hasAbility(string $ability): bool
    {
        return $this->hasPermission($ability);
    }

    /**
     * Helper check for Owner role.
     */
    public function isOwner(): bool
    {
        return $this->hasRole('owner');
    }

    /**
     * Helper check for Employee role.
     */
    public function isEmployee(): bool
    {
        return $this->hasRole('employee');
    }
}
