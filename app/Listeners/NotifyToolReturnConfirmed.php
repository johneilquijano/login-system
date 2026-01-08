<?php

namespace App\Listeners;

use App\Models\AppNotification;

class NotifyToolReturnConfirmed
{
    /**
     * Handle tool return confirmed event
     */
    public function handle($event): void
    {
        $checkout = $event->toolCheckout;

        AppNotification::createNotification(
            user: $checkout->user,
            type: 'tools.return_confirmed',
            title: 'Tool Return Confirmed',
            message: '"' . $checkout->tool_name . '" has been successfully returned.',
            linkUrl: route('tools.index'),
            data: ['checkout_id' => $checkout->id, 'tool_id' => $checkout->tool_id]
        );
    }
}
