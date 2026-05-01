<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $feature
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $tenant = app(\App\Core\Tenancy\TenantManager::class)->getTenant();

        if ($tenant && $tenant->plan) {
            // Check feature availability
            // This is a placeholder for more complex feature logic
            // For now, we can check by plan name or a hardcoded mapping
            if (!$this->isFeatureAvailable($tenant->plan, $feature)) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('messages.feature_locked', ['feature' => $feature]),
                    ], 403);
                }

                return redirect()->back()
                    ->with('error', __('messages.feature_locked', ['feature' => $feature]));
            }
        }

        return $next($request);
    }

    /**
     * Internal logic to determine if a feature is available for a plan.
     */
    protected function isFeatureAvailable($plan, string $feature): bool
    {
        // Example: Only plans with more than 10 devices get access to Reports
        if ($feature === 'reports' && $plan->device_limit <= 5) {
            return false;
        }

        // Add more feature checks here as needed
        
        return true;
    }
}
