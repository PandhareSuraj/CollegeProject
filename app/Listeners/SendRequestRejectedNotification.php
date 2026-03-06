<?php

namespace App\Listeners;

use App\Events\RequestRejected;
use App\Mail\RequestRejectedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendRequestRejectedNotification implements ShouldQueue
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
    public function handle(RequestRejected $event): void
    {
        try {
            // Send email to the requestor
            $requestedBy = $event->request->requestedBy();
            if ($requestedBy && $requestedBy->email) {
                Mail::to($requestedBy->email)
                    ->send(new RequestRejectedNotification($event->request, $event->approval));
            }

            // Send email to department HOD for tracking
            if ($event->request->department) {
                $hod = \App\Models\Hod::find($event->request->department->id);
                if ($hod && $event->approverRole !== 'hod') {
                    Mail::to($hod->email)
                        ->send(new RequestRejectedNotification($event->request, $event->approval));
                }
            }

            // Send email to admin for tracking all rejections
            $admin = \App\Models\Administrator::first();
            if ($admin) {
                Mail::to($admin->email)
                    ->send(new RequestRejectedNotification($event->request, $event->approval));
            }

            Log::warning('Request rejected notification sent', [
                'request_id' => $event->request->id,
                'approver_id' => $event->approval->approved_by,
                'approver_role' => $event->approverRole,
                'reason' => $event->reason,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send request rejected notification', [
                'request_id' => $event->request->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
