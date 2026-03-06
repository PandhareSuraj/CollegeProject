<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckProvider Middleware
 * 
 * Validates that only providers can access supply endpoints.
 * Usage: Route::middleware('check-provider')->post(...)
 */
class CheckProvider
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->isProvider()) {
            \Log::warning('Non-provider attempted provider access', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'requested_url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
            ]);

            abort(403, 'Only providers can access this resource.');
        }

        return $next($request);
    }
}
