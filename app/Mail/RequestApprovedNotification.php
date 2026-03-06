<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestApprovedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Minimal scalar payload only. Avoid serializing full models.
     * Example keys: request_id, approver_name, approver_role, requestor_name, requestor_email,
     * department_name, item_count, total_amount, approval_id, approval_status, approval_remarks
     * @var array
     */
    public array $payload;

    /**
     * Create a new message instance.
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjRole = isset($this->payload['approver_role']) ? ucfirst(str_replace('_',' ',$this->payload['approver_role'])) : 'Approver';
        $requestId = $this->payload['request_id'] ?? 'N/A';
        return new Envelope(
            subject: "Request #{$requestId} Approved by {$subjRole}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.request-approved',
            with: [
                'payload' => $this->payload,
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
