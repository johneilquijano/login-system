<?php

namespace App\Events;

use App\Models\InventoryRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryRequestSubmitted
{
    use Dispatchable, SerializesModels;

    public InventoryRequest $inventoryRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(InventoryRequest $inventoryRequest)
    {
        $this->inventoryRequest = $inventoryRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('inventory-requests'),
        ];
    }
}
