<?php

namespace App\Listeners;

use App\Events\InventoryRequestApproved;
use App\Models\AppNotification;

class NotifyInventoryRequestApproved
{
    /**
     * Handle the event.
     */
    public function handle(InventoryRequestApproved $event): void
    {
        $request = $event->inventoryRequest;

        // Notify the employee who submitted the request
        AppNotification::createNotification(
            user: $request->user,
            type: 'inventory_requests.approved',
            title: 'Request Approved',
            message: 'Your inventory request "' . $request->request_title . '" has been approved and is being prepared.',
            linkUrl: route('inventory-requests.show', $request),
            data: ['request_id' => $request->id]
        );
    }
}
