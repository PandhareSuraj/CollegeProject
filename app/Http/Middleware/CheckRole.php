<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckRole Middleware
 * 
 * Validates user role against allowed roles for the route.
 * Usage: Route::middleware('role:admin,hod')->get(...)
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     * 
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles Allowed roles (comma-separated or variadic)
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to access this resource.');
        }

        $user = auth()->user();

        // Check if user role is in allowed roles
        if ($this->isAuthorized($user, $roles)) {
            return $next($request);
        }

        // Log unauthorized access attempt
        $this->logUnauthorizedAccess($request, $user, $roles);

        // Return 403 Forbidden
        abort(403, 'You do not have permission to access this resource.');
    }

    /**
     * Check if user is authorized for the given roles
     */
    private function isAuthorized($user, array $roles): bool
    {
        // If no roles specified, deny access
        if (empty($roles)) {
            return false;
        }

        // Check if user's role is in the allowed roles
        return in_array($user->role, $roles);
    }

    /**
     * Log unauthorized access attempts
     */
    private function logUnauthorizedAccess(Request $request, $user, array $roles): void
    {
        \Log::warning('Unauthorized access attempt', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'required_roles' => $roles,
            'requested_url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);
    }
}
