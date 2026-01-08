<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// Import events
use App\Events\InventoryRequestApproved;
use App\Events\InventoryRequestDenied;
use App\Events\InventoryRequestFulfilled;
use App\Events\InventoryRequestSubmitted;

// Import listeners
use App\Listeners\NotifyInventoryRequestApproved;
use App\Listeners\NotifyInventoryRequestDenied;
use App\Listeners\NotifyInventoryRequestFulfilled;
use App\Listeners\NotifyAdminsInventoryRequestSubmitted;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // Inventory Request Events
        InventoryRequestApproved::class => [
            NotifyInventoryRequestApproved::class,
        ],
        InventoryRequestDenied::class => [
            NotifyInventoryRequestDenied::class,
        ],
        InventoryRequestFulfilled::class => [
            NotifyInventoryRequestFulfilled::class,
        ],
        InventoryRequestSubmitted::class => [
            NotifyAdminsInventoryRequestSubmitted::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
