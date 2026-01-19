<?php

namespace App\Events\Tracking;


use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

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
//        $exists = FaultLocation::where('user_id',$location->user_id)
//            ->where('state',FaultStatus::Accepted)
//            ->exists();
//        $this->color = $exists? '#008000' : '#FFFF00';
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("gps.location"),
        ];
    }

//    public function broadcastAs(): string
//    {
//        return "gps.location";
//    }
}
