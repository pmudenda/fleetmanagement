<?php

namespace App\Events;

use App\Models\Common\MaterialHeader;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequisitionRaised
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public MaterialHeader $requestHeader;
    public string $action;

    /**
     * Create a new event instance.
     */
    public function __construct(MaterialHeader $requestHeader, string $action)
    {
        $this->requestHeader = $requestHeader;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
