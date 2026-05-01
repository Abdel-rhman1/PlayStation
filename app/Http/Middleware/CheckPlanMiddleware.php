<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanMiddleware
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
            $message = $tenant && $tenant->onTrial() 
                ? __('auth.trial_expired') 
                : __('auth.subscription_expired');

            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 403);
            }

            return redirect()->route('settings.index', ['tab' => 'billing'])
                ->with('error', $message);
        }

        return $next($request);
    }
}
