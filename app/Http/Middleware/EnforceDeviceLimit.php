<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class EnforceDeviceLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info("EnforceDeviceLimit triggered for: " . $request->fullUrl());
        $tenant = app(\App\Core\Tenancy\TenantManager::class)->getTenant();

        if (!$tenant) {
            Log::warning("EnforceDeviceLimit: No tenant found in manager.");
        }

        if ($tenant && !$tenant->plan) {
            Log::warning("EnforceDeviceLimit: Tenant {$tenant->id} has no plan assigned.");
        }

        if ($tenant && $tenant->plan) {
            $deviceCount = $tenant->devices()->count();
            Log::info("EnforceDeviceLimit: Tenant {$tenant->id}, Count: {$deviceCount}, Limit: {$tenant->plan->device_limit}");
            
            if ($deviceCount >= $tenant->plan->device_limit) {
                $message = __('messages.device_limit_reached', [
                    'plan' => $tenant->plan->name,
                    'limit' => $tenant->plan->device_limit
                ]);

                Log::info("EnforceDeviceLimit: Limit reached! Redirecting back with error.");

                // if ($request->expectsJson() || $request->is('api/*')) {
                //     return response()->json([
                //         'status' => 'error',
                //         'message' => $message,
                //     ], 403);
                // }

                return redirect()->back()
                    ->with('error', $message)
                    ->with('error_title', __('messages.limit_reached_title'));
            }
        } else {
             Log::info("EnforceDeviceLimit: Condition not met (No tenant or no plan).");
        }

        return $next($request);
    }
}
