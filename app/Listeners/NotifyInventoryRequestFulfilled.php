<?php

namespace App\Listeners;

use App\Events\InventoryRequestFulfilled;
use App\Models\AppNotification;

class NotifyInventoryRequestFulfilled
{
    /**
     * Handle the event.
     */
    public function handle(InventoryRequestFulfilled $event): void
    {
        $request = $event->inventoryRequest;

        // Notify the employee who submitted the request
        AppNotification::createNotification(
            user: $request->user,
            type: 'inventory_requests.fulfilled',
            title: 'Request Ready for Pickup',
            message: 'Your inventory request "' . $request->request_title . '" is ready for pickup.',
            linkUrl: route('inventory-requests.show', $request),
            data: ['request_id' => $request->id]
        );
    }
}
