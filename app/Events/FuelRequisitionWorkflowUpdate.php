<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FuelRequisitionWorkflowUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public string $reference;
    public Authenticatable|null $user;
    public string $action;
    public string|null $requisitionNumber;

    public function __construct($reference, $user, $action, $requisitionNumber)
    {
        $this->user = $user;
        $this->reference = $reference;
        $this->action = $action;
        $this->requisitionNumber = $requisitionNumber;
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
