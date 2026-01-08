<?php

namespace App\Listeners;

use App\Models\AppNotification;

class NotifyToolDueSoon
{
    /**
     * Handle tool due soon event (typically fired by scheduler)
     */
    public function handle($event): void
    {
        $checkout = $event->toolCheckout;

        AppNotification::createNotification(
            user: $checkout->user,
            type: 'tools.due_soon',
            title: 'Tool Due Soon',
            message: 'Your checkout of "' . $checkout->tool_name . '" is due ' . $checkout->return_due_date->diffForHumans(),
            linkUrl: route('tools.index'),
            data: ['checkout_id' => $checkout->id, 'tool_id' => $checkout->tool_id]
        );
    }
}
