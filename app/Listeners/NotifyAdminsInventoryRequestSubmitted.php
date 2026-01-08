<?php

namespace App\Listeners;

use App\Events\InventoryRequestSubmitted;
use App\Models\AppNotification;
use App\Models\User;

class NotifyAdminsInventoryRequestSubmitted
{
    /**
     * Handle the event to notify all admins of a new inventory request
     */
    public function handle(InventoryRequestSubmitted $event): void
    {
        $request = $event->inventoryRequest;

        // Get all admins in the organization
        $admins = User::forOrganization($request->org_id)
            ->where('role', 'admin')
            ->get();

        // Create notification for each admin
        foreach ($admins as $admin) {
            AppNotification::createNotification(
                user: $admin,
                type: 'inventory_requests.submitted',
                title: 'New Inventory Request',
                message: 'New request "' . $request->request_title . '" from ' . $request->user->name . ' (' . ucfirst($request->priority) . ' priority)',
                linkUrl: route('admin.inventory-requests.show', $request),
                data: ['request_id' => $request->id]
            );
        }
    }
}
