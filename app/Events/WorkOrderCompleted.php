<?php

namespace App\Events;

use App\Models\WorkShopManagement\JobCardHeader;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkOrderCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public JobCardHeader $workOrder;

    /**
     * Create a new event instance.
     */
    public function __construct(JobCardHeader $workOrder)
    {
        $this->workOrder = $workOrder;
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
