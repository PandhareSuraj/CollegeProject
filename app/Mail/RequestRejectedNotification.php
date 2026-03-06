<?php

namespace App\Mail;

use App\Models\StationaryRequest;
use App\Models\Approval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestRejectedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public StationaryRequest $request;
    public Approval $approval;

    /**
     * Create a new message instance.
     */
    public function __construct(StationaryRequest $request, Approval $approval)
    {
        $this->request = $request;
        $this->approval = $approval;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Request #' . $this->request->id . ' Rejected by ' . ucfirst(str_replace('_', ' ', $this->approval->role)),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.request-rejected',
            with: [
                'request' => $this->request,
                'approval' => $this->approval,
                'approverName' => $this->approval->approvedBy->name,
                'approverRole' => ucfirst(str_replace('_', ' ', $this->approval->role)),
                'requestorName' => $this->request->requestedBy->name,
                'departmentName' => $this->request->department->name,
                'reason' => $this->approval->remarks ?: 'No reason provided',
                'itemCount' => $this->request->items->count(),
                'totalAmount' => $this->request->total_amount,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
