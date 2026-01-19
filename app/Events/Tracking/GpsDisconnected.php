<?php

namespace App\Events\Tracking;

use App\Models\VehicleManagement\Tracking\Gps;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GpsDisconnected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Gps $gps;

    /**
     * Create a new event instance.
     */
    public function __construct(Gps $gps)
    {
        $this->gps = $gps;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("gps.disconnected"),
        ];
    }
}
