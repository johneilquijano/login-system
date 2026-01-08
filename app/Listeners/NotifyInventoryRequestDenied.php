<?php

namespace App\Listeners;

use App\Events\InventoryRequestDenied;
use App\Models\AppNotification;

class NotifyInventoryRequestDenied
{
    /**
     * Handle the event.
     */
    public function handle(InventoryRequestDenied $event): void
    {
        $request = $event->inventoryRequest;

        // Notify the employee who submitted the request
        AppNotification::createNotification(
            user: $request->user,
            type: 'inventory_requests.denied',
            title: 'Request Denied',
            message: 'Your inventory request "' . $request->request_title . '" has been denied. Check the details for more info.',
            linkUrl: route('inventory-requests.show', $request),
            data: ['request_id' => $request->id]
        );
    }
}
