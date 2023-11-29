<?php

namespace App\Events;

use App\Models\WorkShopManagement\JobCardHeader;
use App\Models\WorkShopManagement\Mechanic;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobCardCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public Mechanic|null $supervisor;
    public JobCardHeader|null $jobCard;

    /**
     * Create a new event instance.
     */
    public function __construct($user, Mechanic|null $supervisor, JobCardHeader|null $jobCard)
    {
        $this->user = $user;
        $this->supervisor = $supervisor;
        $this->jobCard = $jobCard;
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
