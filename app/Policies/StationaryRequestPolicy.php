<?php

namespace App\Policies;

use App\Models\StationaryRequest;
use App\Models\User;

class StationaryRequestPolicy
{
    /**
     * Determine if the user can view the request
     */
    public function view(User $user, StationaryRequest $request): bool
    {
        // Admin can view all
        if ($user->isAdmin()) {
            return true;
        }

        // Teachers see their own requests
        if ($user->isTeacher()) {
            return (int) $request->requested_by === (int) $user->id;
        }

        // HOD sees their department's requests
        if ($user->isHOD()) {
            return $request->department_id === $user->department_id;
        }

        // Principal, Trust Head see all requests
        if ($user->isPrincipal() || $user->isTrustHead()) {
            return true;
        }

        // Provider can view only supplied/completed requests
        if ($user->isProvider()) {
            return in_array($request->status, ['sent_to_provider', 'completed']);
        }

        return false;
    }

    /**
     * Determine if the user can create a request
     */
    public function create(User $user): bool
    {
        // Only teachers, HOD can create requests
        return $user->isTeacher() || $user->isHOD();
    }

    /**
     * Determine if the user can update the request
     */
    public function update(User $user, StationaryRequest $request): bool
    {
        // Only requestor can edit pending requests
        if ($request->status === 'pending') {
            return (int) $request->requested_by === (int) $user->id || $user->isAdmin();
        }

        return false;
    }

    /**
     * Determine if the user can delete the request
     */
    public function delete(User $user, StationaryRequest $request): bool
    {
        // Only requestor can delete pending requests
        if ($request->status === 'pending') {
            return (int) $request->requested_by === (int) $user->id || $user->isAdmin();
        }

        return false;
    }

    /**
     * Determine if the user can approve the request
     */
    public function approve(User $user, StationaryRequest $request): bool
    {
        // Can't approve own request
    if ((int) $request->requested_by === (int) $user->id) {
            return false;
        }

        // HOD approval
        if ($user->isHOD()) {
            return $request->status === 'pending' && $request->department_id === $user->department_id;
        }

        // Principal approval
        if ($user->isPrincipal()) {
            return $request->status === 'hod_approved';
        }

        // Trust Head approval
        if ($user->isTrustHead()) {
            return $request->status === 'principal_approved';
        }

        // Admin approval
        if ($user->isAdmin()) {
            return in_array($request->status, ['pending', 'hod_approved', 'principal_approved', 'trust_approved']);
        }

        return false;
    }

    /**
     * Determine if the user can reject the request
     */
    public function reject(User $user, StationaryRequest $request): bool
    {
        // Can't reject own request
        if ($request->requested_by === $user->id) {
            return false;
        }

        return $this->approve($user, $request);
    }
}
