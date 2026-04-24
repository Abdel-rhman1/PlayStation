<?php

namespace App\Core\Traits;

use App\Services\UserAccessService;
use Illuminate\Database\Eloquent\Builder;

trait HasBranchAccess
{
    /**
     * Scope a query to only include records from accessible branches.
     */
    public function scopeAccessibleBy(Builder $query, $user = null): Builder
    {
        $user = $user ?: auth()->user();

        if (!$user) {
            return $query;
        }

        $service = app(UserAccessService::class);
        $accessibleIds = $service->getAccessibleBranchIds($user);

        return $query->whereIn('branch_id', $accessibleIds);
    }
}
