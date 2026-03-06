<?php

namespace App\Services;

use App\Models\StationaryRequest;
use App\Models\Approval;
use App\Models\User;
use App\Enums\RequestStatus;
use App\Enums\ApprovalRole;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApprovalWorkflowService
{
    /**
     * Check if user can approve a request
     */
    public function canApprove(User $user, StationaryRequest $request): bool
    {
    // Cannot approve own request
    if ((int) $request->requested_by === (int) $user->id) {
            return false;
        }

        // Cannot approve completed or rejected requests
        if ($request->status === 'completed' || $request->status === 'rejected') {
            return false;
        }

        // Check role-specific conditions
        return match ($user->role) {
            'hod' => $this->canHodApprove($user, $request),
            'principal' => $this->canPrincipalApprove($request),
            'trust_head' => $this->canTrustHeadApprove($request),
            'admin' => $this->canAdminApprove($request),
            default => false,
        };
    }

    /**
     * Check if HOD can approve
     */
    private function canHodApprove(User $user, StationaryRequest $request): bool
    {
        // HOD must be from same department
        if ($request->department_id !== $user->department_id) {
            return false;
        }

        // Request must be pending
        if ($request->status !== 'pending') {
            return false;
        }

        // HOD cannot approve twice
        return !$this->hasAlreadyApproved($request, 'hod');
    }

    /**
     * Check if Principal can approve
     */
    private function canPrincipalApprove(StationaryRequest $request): bool
    {
        // Request must be HOD approved
        if ($request->status !== 'hod_approved') {
            return false;
        }

        // Principal cannot approve twice
        return !$this->hasAlreadyApproved($request, 'principal');
    }

    /**
     * Check if Trust Head can approve
     */
    private function canTrustHeadApprove(StationaryRequest $request): bool
    {
        // Request must be Principal approved
        if ($request->status !== 'principal_approved') {
            return false;
        }

        // Trust Head cannot approve twice
        return !$this->hasAlreadyApproved($request, 'trust_head');
    }

    /**
     * Check if Admin can approve
     */
    private function canAdminApprove(StationaryRequest $request): bool
    {
        // Admin can approve at most stages
        $approvalStages = ['pending', 'hod_approved', 'principal_approved', 'trust_approved'];
        return in_array($request->status, $approvalStages);
    }

    /**
     * Check if role has already approved this request
     */
    private function hasAlreadyApproved(StationaryRequest $request, string $role): bool
    {
        return Approval::where('request_id', $request->id)
            ->where('role', $role)
            ->exists();
    }

    /**
     * Process approval action
     */
    public function processApproval(
        StationaryRequest $request,
        User $approver,
        string $decision,
        ?string $remarks = null
    ): Approval {
        if (!in_array($decision, ['approved', 'rejected'])) {
            throw new \InvalidArgumentException('Decision must be "approved" or "rejected"');
        }

        // Create approval record
        $approval = Approval::create([
            'request_id' => $request->id,
            'approved_by' => $approver->id,
            'role' => $approver->role,
            'status' => $decision,
            'remarks' => $remarks,
        ]);

        // Update request status based on decision
        if ($decision === 'rejected') {
            $request->update(['status' => 'rejected']);
        } else {
            // Advance to next status
            $nextStatus = $this->getNextStatus($approver->role);
            $request->update(['status' => $nextStatus]);
        }

        return $approval;
    }

    /**
     * Get next workflow status based on role
     */
    private function getNextStatus(string $role): string
    {
        return match ($role) {
            'hod' => 'hod_approved',
            'principal' => 'principal_approved',
            'trust_head' => 'trust_approved',
            'admin' => 'sent_to_provider',
            default => 'pending',
        };
    }

    /**
     * Get approval timeline
     */
    public function getApprovalTimeline(StationaryRequest $request): array
    {
        return $request->approvals()
            ->with('approver')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function (Approval $approval) {
                return [
                    'id' => $approval->id,
                    'role' => ucfirst(str_replace('_', ' ', $approval->role)),
                    'approver_name' => optional($approval->approver)->name ?? 'Unknown',
                    'approver_email' => optional($approval->approver)->email ?? null,
                    'status' => ucfirst($approval->status),
                    'date' => $approval->created_at->format('M d, Y H:i A'),
                    'remarks' => $approval->remarks,
                    'status_color' => $this->getStatusColor($approval->status),
                ];
            })
            ->toArray();
    }

    /**
     * Get status color for display
     */
    private function getStatusColor(string $status): string
    {
        return match ($status) {
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'warning',
        };
    }

    /**
     * Get workflow stage display info
     */
    public function getWorkflowStageInfo(StationaryRequest $request): array
    {
        return [
            'current_stage' => $this->formatStatus($request->status),
            'is_pending' => $request->status === 'pending',
            'is_hod_approved' => $request->status === 'hod_approved',
            'is_principal_approved' => $request->status === 'principal_approved',
            'is_trust_approved' => $request->status === 'trust_approved',
            'is_sent_to_provider' => $request->status === 'sent_to_provider',
            'is_completed' => $request->status === 'completed',
            'is_rejected' => $request->status === 'rejected',
            'is_final' => in_array($request->status, ['completed', 'rejected']),
        ];
    }

    /**
     * Format status for display
     */
    private function formatStatus(string $status): string
    {
        return match ($status) {
            'pending' => 'Pending HOD Approval',
            'hod_approved' => 'Approved by HOD - Awaiting Principal',
            'principal_approved' => 'Approved by Principal - Awaiting Trust Head',
            'trust_approved' => 'Approved by Trust Head - Ready for Supply',
            'sent_to_provider' => 'Sent to Provider',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    /**
     * Get pending approvals for a user
     */
    public function getPendingApprovalsForUser(User $user): \Illuminate\Database\Eloquent\Collection
    {
        $query = StationaryRequest::query();

        $statusFilter = match ($user->role) {
            'hod' => 'pending',
            'principal' => 'hod_approved',
            'trust_head' => 'principal_approved',
            'admin' => ['pending', 'hod_approved', 'principal_approved', 'trust_approved'],
            default => null,
        };

        if (!$statusFilter) {
            return collect([]);
        }

        $query = is_array($statusFilter)
            ? $query->whereIn('status', $statusFilter)
            : $query->where('status', $statusFilter);

        if ($user->isHOD()) {
            $query->where('department_id', $user->department_id);
        }

        return $query->with(['department', 'requestedBy', 'items'])
                    ->latest()
                    ->get();
    }

    /**
     * Get approval statistics for a user
     */
    public function getApprovalStats(User $user): array
    {
        $pending = $this->getPendingApprovalsForUser($user)->count();

        $approved = Approval::where('approved_by', $user->id)
            ->where('status', 'approved')
            ->count();

        $rejected = Approval::where('approved_by', $user->id)
            ->where('status', 'rejected')
            ->count();

        return [
            'pending_approvals' => $pending,
            'total_approved' => $approved,
            'total_rejected' => $rejected,
        ];
    }
}
