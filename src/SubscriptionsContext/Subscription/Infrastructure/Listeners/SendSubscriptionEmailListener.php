<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Listeners;

use Src\SubscriptionsContext\Subscription\Domain\Events\SubscriptionCreated;
use Src\SubscriptionsContext\Subscription\Infrastructure\Jobs\Laravel\SendSubscriptionEmailJob;

final class SendSubscriptionEmailListener
{
    public function handle(SubscriptionCreated $event): void
    {
        SendSubscriptionEmailJob::dispatch(
            $event->userId,
            $event->planId,
            $event->expiresAt
        );
    }
}

