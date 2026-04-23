<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = app(\App\Core\Tenancy\TenantManager::class)->getTenant();

        if (!$tenant || !$tenant->hasActiveSubscription()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Subscription expired or inactive. Please upgrade your plan.',
                ], 403);
            }

            return redirect()->route('settings.index', ['tab' => 'billing'])
                ->with('error', 'Your subscription has expired. Please upgrade to continue.');
        }

        return $next($request);
    }
}
