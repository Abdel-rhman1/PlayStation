<?php

namespace App\Services;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;

class UserAccessService
{
    /**
     * Get all branches the user is allowed to access.
     */
    public function getAccessibleBranches(User $user): Collection
    {
        // If the user's role has full branch access, return all branches for the tenant
        if ($user->role?->has_full_branch_access) {
            return Branch::all();
        }

        // Otherwise, return only the specific branches assigned to the user
        return $user->branches;
    }

    /**
     * Get IDs of accessible branches.
     */
    public function getAccessibleBranchIds(User $user): array
    {
        return $this->getAccessibleBranches($user)->pluck('id')->toArray();
    }
}
