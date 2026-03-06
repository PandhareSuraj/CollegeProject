<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\StationaryRequest;
use App\Models\Approval;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckApprovalAccess Middleware
 * 
 * Validates user can approve/reject the specific request.
 * Checks workflow status, user role, and approval eligibility.
 * Usage: Route::middleware('check-approval')->post(...)
 */
class CheckApprovalAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Try to get the request parameter from different possible route parameters
        $requestId = $request->route('stationaryRequest') ?? $request->route('request');

        // If no route parameter found, skip the middleware
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

        // Check if user can approve this request
        // Allow viewing the approval page (GET) so the reviewer can see details.
        // Actual approval eligibility will be enforced when submitting (POST).
        if ($request->isMethod('GET')) {
            return $next($request);
        }

        if (!$this->canApproveRequest($user, $stationaryRequest)) {
            \Log::warning('Unauthorized approval attempt', [
                'user_id' => $user?->id ?? null,
                'user_role' => $user?->role ?? null,
                'request_id' => $stationaryRequest->id,
                'request_status' => $stationaryRequest->status,
                'requested_by' => $stationaryRequest->requested_by,
                'ip_address' => $request->ip(),
            ]);

            // Redirect back to the approval page with a helpful error message so the reviewer sees why
            $message = 'You are not authorized to approve this request at this stage.';
            $details = sprintf(' Role: %s | Request status: %s | Requested by: %s', $user?->role ?? 'unknown', $stationaryRequest->status, $stationaryRequest->requested_by);

            return redirect()->route('approvals.show', $stationaryRequest->id)
                ->with('error', $message . $details);
        }

        return $next($request);
    }

    /**
     * Check if user can approve this request
     */
    private function canApproveRequest($user, StationaryRequest $stationaryRequest): bool
    {
    // Cannot approve own request (requested_by may be id or email)
    if ($stationaryRequest->requested_by == $user->id || $stationaryRequest->requested_by == $user->email) {
            return false;
        }

        // Cannot approve completed or rejected requests
        if (in_array($stationaryRequest->status, ['completed', 'rejected'])) {
            return false;
        }

        // HOD approval
        if ($user->isHOD()) {
            if ($stationaryRequest->status !== 'pending') {
                return false;
            }
            // If user's department is set, require it to match the request. If not set, allow (fallback).
            if (!empty($user->department_id) && $stationaryRequest->department_id !== $user->department_id) {
                return false;
            }
            // Check if HOD has already approved
            return !Approval::where('request_id', $stationaryRequest->id)
                ->where('role', 'hod')
                ->exists();
        }

        // Principal approval
        if ($user->isPrincipal()) {
            if ($stationaryRequest->status !== 'hod_approved') {
                return false;
            }
            return !Approval::where('request_id', $stationaryRequest->id)
                ->where('role', 'principal')
                ->exists();
        }

        // Trust Head approval
        if ($user->isTrustHead()) {
            if ($stationaryRequest->status !== 'principal_approved') {
                return false;
            }
            return !Approval::where('request_id', $stationaryRequest->id)
                ->where('role', 'trust_head')
                ->exists();
        }

        // Admin can approve at most stages
        if ($user->isAdmin()) {
            return !in_array($stationaryRequest->status, ['sent_to_provider', 'completed', 'rejected']);
        }

        return false;
    }
}
