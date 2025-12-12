<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Src\SubscriptionsContext\Subscription\Domain\Events\SubscriptionCreated;
use Src\SubscriptionsContext\Subscription\Infrastructure\Listeners\{
    SendSubscriptionEmailListener,
    LogSubscriptionAuditListener
};

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        SubscriptionCreated::class => [
            SendSubscriptionEmailListener::class,
            LogSubscriptionAuditListener::class,
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

