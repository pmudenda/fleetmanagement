<?php

namespace App\Events;

use App\Models\Common\MaterialHeader;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaterialReservationMade
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public MaterialHeader $materialHeader;
    public string $requestType;

    /**
     * Create a new event instance.
     */
    public function __construct(MaterialHeader $materialHeader, string $requestType)
    {
        $this->materialHeader = $materialHeader;
        $this->requestType = $requestType;
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
