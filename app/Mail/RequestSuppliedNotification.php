<?php

namespace App\Mail;

use App\Models\StationaryRequest;
use App\Models\Provider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestSuppliedNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public StationaryRequest $request;
    public Provider $supplier;

    /**
     * Create a new message instance.
     */
    public function __construct(StationaryRequest $request, Provider $supplier)
    {
        $this->request = $request;
        $this->supplier = $supplier;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Request #' . $this->request->id . ' Successfully Supplied and Completed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.request-supplied',
            with: [
                'request' => $this->request,
                'supplier' => $this->supplier,
                'requestorName' => $this->request->requester->name,
                'departmentName' => $this->request->department->name,
                'itemCount' => $this->request->items->count(),
                'totalAmount' => $this->request->total_amount,
                'completedDate' => now()->format('d M, Y'),
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
