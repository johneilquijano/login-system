<?php

namespace App\Notifications;

use App\Models\InventoryRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InventoryRequestDeniedNotification extends Notification
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
        return ['database'];
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
            'message' => 'Your inventory request "' . $this->inventoryRequest->request_title . '" has been denied',
            'reason' => $this->inventoryRequest->denied_reason,
            'type' => 'inventory_request_denied',
            'url' => route('inventory-requests.show', $this->inventoryRequest),
        ];
    }
}
