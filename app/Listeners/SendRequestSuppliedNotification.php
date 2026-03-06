<?php

namespace App\Listeners;

use App\Events\RequestSupplied;
use App\Mail\RequestSuppliedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendRequestSuppliedNotification implements ShouldQueue
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
    public function handle(RequestSupplied $event): void
    {
        try {
            // Send email to the requestor (queued)
            $requestedBy = $event->request->requester;
            if ($requestedBy && $requestedBy->email) {
                Mail::to($requestedBy->email)
                    ->queue(new RequestSuppliedNotification($event->request, $event->supplier));
            }

            // Send email to department HOD for tracking
            if ($event->request->department) {
                $hod = \App\Models\Hod::find($event->request->department->id);
                if ($hod && $hod->email) {
                    Mail::to($hod->email)
                        ->queue(new RequestSuppliedNotification($event->request, $event->supplier));
                }
            }

            // Send email to admin for tracking all supplies
            $admin = \App\Models\Administrator::first();
            if ($admin && $admin->email) {
                Mail::to($admin->email)
                    ->queue(new RequestSuppliedNotification($event->request, $event->supplier));
            }

            Log::info('Request supplied notification sent', [
                'request_id' => $event->request->id,
                'supplier_id' => $event->supplier->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send request supplied notification', [
                'request_id' => $event->request->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
