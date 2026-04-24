<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Check that the authenticated user has the given permission.
     *
     * Usage in routes:
     *   Route::middleware('permission:devices.manage')
     *   Route::middleware('permission:devices.manage,orders.view')  // all required
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (!$request->user()) {
            abort(403);
        }

        // Safe Rollout: If no roles have been defined for this tenant yet, allow access.
        $roleCount = \App\Models\Role::count(); // Scoped by tenant_id automatically via global scope
        if ($roleCount === 0) {
            return $next($request);
        }

        foreach ($permissions as $permission) {
            if (!$request->user()->can(trim($permission))) {
                abort(403, 'You do not have the required permission to access this resource.');
            }
        }

        return $next($request);
    }
}
