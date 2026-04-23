<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceDeviceLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = app(\App\Core\Tenancy\TenantManager::class)->getTenant();

        if ($tenant && $tenant->plan) {
            $deviceCount = $tenant->devices()->count();
            
            if ($deviceCount >= $tenant->plan->device_limit) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Device limit reached for your {$tenant->plan->name} plan.",
                ], 403);
            }
        }

        return $next($request);
    }
}
