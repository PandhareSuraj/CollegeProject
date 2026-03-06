<?php

namespace App\Listeners;

use App\Events\RequestApproved;
use App\Mail\RequestApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendRequestApprovedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RequestApproved $event): void
    {
        try {
            // Prepare a minimal payload to avoid serializing full models
            $approverName = optional($event->approval->approver)->name ?? null;
            $approverEmail = optional($event->approval->approver)->email ?? null;
            $payload = [
                'request_id' => $event->request->id,
                'approval_id' => $event->approval->id,
                'approval_status' => $event->approval->status,
                'approval_remarks' => $event->approval->remarks,
                'approver_name' => $approverName,
                'approver_email' => $approverEmail,
                'approver_role' => $event->approval->role,
                'requestor_name' => optional($event->request->requester)->name ?? null,
                'requestor_email' => optional($event->request->requester)->email ?? null,
                'department_name' => optional($event->request->department)->name ?? null,
                'item_count' => $event->request->items()->count(),
                'total_amount' => $event->request->total_amount,
            ];

            // Queue email to the requestor (non-blocking)
            if (!empty($payload['requestor_email'])) {
                Mail::to($payload['requestor_email'])->queue(new RequestApprovedNotification($payload));
            }

            // Send email to next level approvers (except if already at final stage)
            $this->notifyNextApprovers($event, $payload);

            // Send email to department HOD for tracking (queued)
            if ($event->request->department && $event->request->department->hod && $event->approverRole !== 'hod') {
                $hodEmail = $event->request->department->hod->email ?? null;
                if ($hodEmail) {
                    Mail::to($hodEmail)->queue(new RequestApprovedNotification($payload));
                }
            }

            Log::info('Request approved notification sent', [
                'request_id' => $event->request->id,
                'approver_id' => $event->approval->approved_by,
                'approver_role' => $event->approverRole,
                'requestor_email' => $payload['requestor_email'],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send request approved notification', [
                'request_id' => $event->request->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
    /**
     * Notify the next level approvers based on workflow
     */
    private function notifyNextApprovers(RequestApproved $event, array $payload = []): void
    {
        // Determine who should be notified based on the approver role.
        // Map current approver role to the next approver role in workflow.
        $workflowNextRole = [
            'hod' => 'principal',
            'principal' => 'trust_head',
            'trust_head' => 'provider',
            'provider' => null, // provider is usually final (no next approver)
        ];

        $currentApprover = $event->approverRole;
        $nextRole = $workflowNextRole[$currentApprover] ?? null;

        if ($nextRole) {
            $nextUsers = $this->getUsersByRole($nextRole);

            foreach ($nextUsers as $user) {
                // Avoid sending to the same user who just approved
                if ($user->email === ($event->approval->approver_email ?? null)) {
                    continue;
                }

                // Queue the mail so sending doesn't block the listener
                if (!empty($user->email)) {
                    // Use payload prepared earlier if available on event; otherwise build minimal payload
                    $approverName = optional($event->approval->approver)->name ?? null;
                    $payload = [
                        'request_id' => $event->request->id,
                        'approval_id' => $event->approval->id,
                        'approval_status' => $event->approval->status,
                        'approval_remarks' => $event->approval->remarks,
                        'approver_name' => $approverName,
                        'approver_role' => $event->approval->role,
                        'requestor_name' => optional($event->request->requester)->name ?? null,
                        'requestor_email' => optional($event->request->requester)->email ?? null,
                        'department_name' => optional($event->request->department)->name ?? null,
                        'item_count' => $event->request->items()->count(),
                        'total_amount' => $event->request->total_amount,
                    ];

                    Mail::to($user->email)->queue(new RequestApprovedNotification($payload));
                }
            }
        }
    }

    /**
     * Get user by role
     */
    /**
     * Return a Collection of users for a given role.
     */
    private function getUsersByRole(string $role)
    {
        $roleModelMap = [
            'teacher' => \App\Models\Teacher::class,
            'hod' => \App\Models\Hod::class,
            'principal' => \App\Models\Principal::class,
            'trust_head' => \App\Models\TrustHead::class,
            'provider' => \App\Models\Provider::class,
            'admin' => \App\Models\Administrator::class,
        ];

        $modelClass = $roleModelMap[$role] ?? null;
        if ($modelClass) {
            // Only select minimal columns we need (email for notifications)
            return $modelClass::query()->select('id','name','email')->get();
        }

        return collect();
    }
}
