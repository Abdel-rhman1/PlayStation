<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureShiftIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->shifts()->active()->exists()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('shifts.shift_required')
                ], 403);
            }

            return redirect()->route('shifts.index')
                ->with('error', __('shifts.shift_required'));
        }

        return $next($request);
    }
}
