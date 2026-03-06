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
            // Send email to the requestor
            Mail::to($event->request->requestedBy->email)
                ->send(new RequestApprovedNotification($event->request, $event->approval));

            // Send email to next level approvers (except if already at final stage)
            $this->notifyNextApprovers($event);

            // Send email to department HOD for tracking
            if ($event->request->department && $event->request->department->hod && $event->approverRole !== 'hod') {
                Mail::to($event->request->department->hod->email)
                    ->send(new RequestApprovedNotification($event->request, $event->approval));
            }

            Log::info('Request approved notification sent', [
                'request_id' => $event->request->id,
                'approver_id' => $event->approval->approved_by,
                'approver_role' => $event->approverRole,
                'requestor_email' => $event->request->requestedBy->email,
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
    private function notifyNextApprovers(RequestApproved $event): void
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
                Mail::to($user->email)
                    ->queue(new RequestApprovedNotification($event->request, $event->approval));
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
            return $modelClass::query()->get();
        }

        return collect();
    }
}
