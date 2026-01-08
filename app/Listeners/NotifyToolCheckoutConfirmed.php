<?php

namespace App\Listeners;

use App\Models\AppNotification;

class NotifyToolCheckoutConfirmed
{
    /**
     * Handle tool checkout confirmed event
     */
    public function handle($event): void
    {
        $checkout = $event->toolCheckout;

        AppNotification::createNotification(
            user: $checkout->user,
            type: 'tools.checkout_confirmed',
            title: 'Tool Checkout Confirmed',
            message: 'You have successfully checked out "' . $checkout->tool_name . '". Due date: ' . $checkout->return_due_date->format('M d, Y'),
            linkUrl: route('tools.index'),
            data: ['checkout_id' => $checkout->id, 'tool_id' => $checkout->tool_id]
        );
    }
}
