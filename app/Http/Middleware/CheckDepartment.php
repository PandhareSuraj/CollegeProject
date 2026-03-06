<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckDepartment Middleware
 * 
 * Validates that user is assigned to the correct department.
 * Usage: Route::middleware('department')->get(...)
 */
class CheckDepartment
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

        // Admin can access any department
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user has a department assigned (except admin and provider roles)
        if (!in_array($user->role, ['admin', 'provider']) && !$user->department_id) {
            \Log::warning('User without department attempted access', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'requested_url' => $request->fullUrl(),
            ]);

            abort(403, 'You must be assigned to a department to access this resource.');
        }

        return $next($request);
    }
}
