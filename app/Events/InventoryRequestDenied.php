<?php

namespace App\Events;

use App\Models\InventoryRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryRequestDenied
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public InventoryRequest $inventoryRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(InventoryRequest $inventoryRequest)
    {
        $this->inventoryRequest = $inventoryRequest;
    }
}
