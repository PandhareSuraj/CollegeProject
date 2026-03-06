<?php

namespace App\Events;

use App\Models\StationaryRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestSupplied
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public StationaryRequest $request;
    public Model $supplier;

    /**
     * Create a new event instance.
     */
    public function __construct(StationaryRequest $request, Model $supplier)
    {
        $this->request = $request;
        $this->supplier = $supplier;
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
