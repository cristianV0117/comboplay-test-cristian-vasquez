<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Listeners;

use Src\SubscriptionsContext\Subscription\Domain\Events\SubscriptionCreated;
use Src\SubscriptionsContext\Subscription\Infrastructure\Jobs\Laravel\LogSubscriptionAuditJob;

final class LogSubscriptionAuditListener
{
    public function handle(SubscriptionCreated $event): void
    {
        LogSubscriptionAuditJob::dispatch(
            $event->userId,
            $event->planId,
            'created'
        );
    }
}

