<?php

namespace App\Events;

use App\Models\StationaryRequest;
use App\Models\Approval;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestRejected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public StationaryRequest $request;
    public Approval $approval;
    public string $approverRole;
    public string $reason;

    /**
     * Create a new event instance.
     */
    public function __construct(StationaryRequest $request, Approval $approval, string $reason = '')
    {
        $this->request = $request;
        $this->approval = $approval;
        $this->approverRole = $approval->role;
        $this->reason = $reason ?: $approval->remarks;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('request.' . $this->request->id),
        ];
    }
}
