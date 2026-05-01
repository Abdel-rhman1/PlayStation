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
                $message = "Your current plan '{$tenant->plan->name}' is limited to {$tenant->plan->device_limit} devices. Please upgrade to add more.";

                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $message,
                    ], 403);
                }

                return redirect()->back()
                    ->with('error', $message);
            }
        }

        return $next($request);
    }
}
