<?php

namespace App\Notifications;

use App\Models\InventoryRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InventoryRequestFulfilledNotification extends Notification
{
    use Queueable;

    public InventoryRequest $inventoryRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(InventoryRequest $inventoryRequest)
    {
        $this->inventoryRequest = $inventoryRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return []; // Using AppNotification system via listeners for database storage
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'request_id' => $this->inventoryRequest->id,
            'request_title' => $this->inventoryRequest->request_title,
            'message' => 'Your inventory request "' . $this->inventoryRequest->request_title . '" has been fulfilled. Ready for pickup!',
            'type' => 'inventory_request_fulfilled',
            'url' => route('inventory-requests.show', $this->inventoryRequest),
        ];
    }
}
