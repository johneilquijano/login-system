<?php

namespace App\Notifications;

use App\Models\InventoryRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InventoryRequestSubmittedNotification extends Notification
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
            'request_number' => str_pad($this->inventoryRequest->id, 4, '0', STR_PAD_LEFT),
            'employee_name' => $this->inventoryRequest->user->name,
            'request_title' => $this->inventoryRequest->request_title,
            'priority' => $this->inventoryRequest->priority,
            'message' => 'New inventory request submitted by ' . $this->inventoryRequest->user->name,
            'type' => 'inventory_request_submitted',
            'url' => route('admin.inventory-requests.show', $this->inventoryRequest),
        ];
    }
}
