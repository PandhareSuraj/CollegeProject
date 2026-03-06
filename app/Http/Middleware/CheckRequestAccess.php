<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\StationaryRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckRequestAccess Middleware
 * 
 * Validates user can access/modify the specific request.
 * Checks department ownership, request status, and role permissions.
 * Usage: Route::middleware('check-request')->get(...)
 */
class CheckRequestAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Try to get the request parameter from different possible route parameters
        $requestId = $request->route('stationaryRequest') ?? $request->route('request');

        // If no route parameter found, skip the middleware (e.g., for /requests index)
        if (!$requestId) {
            return $next($request);
        }

        // If requestId is an object (implicit model binding), use it directly
        if ($requestId instanceof StationaryRequest) {
            $stationaryRequest = $requestId;
        } else {
            // Otherwise, try to find it by ID
            $stationaryRequest = StationaryRequest::find($requestId);

            if (!$stationaryRequest) {
                abort(404, 'Request not found.');
            }
        }

        // Check if user can access this request
        if (!$this->canAccessRequest($user, $stationaryRequest, $request->method())) {
            \Log::warning('Unauthorized request access attempt', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'request_id' => $stationaryRequest->id,
                'request_status' => $stationaryRequest->status,
                'http_method' => $request->method(),
                'requested_url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
            ]);

            abort(403, 'You do not have permission to access this request.');
        }

        return $next($request);
    }

    /**
     * Check if user can access the request based on role and method
     */
    private function canAccessRequest($user, StationaryRequest $stationaryRequest, string $method): bool
    {
        // Admin can access all
        if ($user->isAdmin()) {
            return true;
        }

        // Teacher can only view and edit own pending requests
        if ($user->isTeacher()) {
            // cast to int to avoid string/integer mismatches from DB drivers
            if ((int) $stationaryRequest->requested_by !== (int) $user->id) {
                return false;
            }
            
            return $method === 'GET' || ($method !== 'DELETE' && $stationaryRequest->status === 'pending');
        }

        // HOD can view and edit own pending, or view department requests
        if ($user->isHOD()) {
            // Own request
            if ((int) $stationaryRequest->requested_by === (int) $user->id) {
                return $method === 'GET' || ($method !== 'DELETE' && $stationaryRequest->status === 'pending');
            }
            // Department request
            return $stationaryRequest->department_id === $user->department_id && $method === 'GET';
        }

        // Principal, Trust Head, Provider can view sent/supplied requests
        if ($user->isPrincipal() || $user->isTrustHead()) {
            return $method === 'GET';
        }

        if ($user->isProvider()) {
            return $method === 'GET' && in_array($stationaryRequest->status, ['sent_to_provider', 'completed']);
        }

        return false;
    }
}
