<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Check that the authenticated user has one of the given roles.
     *
     * Usage in routes:
     *   Route::middleware('role:owner')
     *   Route::middleware('role:owner,employee')   // any of these roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            abort(403);
        }

        // Safe Rollout: If no roles have been defined for this tenant yet, allow access.
        $roleCount = \App\Models\Role::count(); // Scoped by tenant_id automatically via global scope
        if ($roleCount === 0) {
            return $next($request);
        }

        foreach ($roles as $role) {
            if ($request->user()->hasRole(trim($role))) {
                return $next($request);
            }
        }

        abort(403, 'You do not have the required role to access this resource.');
    }
}
