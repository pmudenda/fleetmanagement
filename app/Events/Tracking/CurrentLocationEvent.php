<?php

namespace App\Events\Tracking;


use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CurrentLocationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public  $location;

    /**
     * Create a new event instance.
     */
    public function __construct( $location)
    {
        $this->location = $location;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        Log::info("BROADCASTING ON gps.{$this->location['imei']}");
        return [
            new Channel("gps"),
        ];
    }

    public function broadcastAs(): string
    {
        return "CurrentLocation";
    }
}
