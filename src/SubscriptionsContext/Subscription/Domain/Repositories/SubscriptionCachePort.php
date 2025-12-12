<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Domain\Repositories;

use Src\SubscriptionsContext\Subscription\Domain\Subscription;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\{
    SubscriptionStoreCache,
    UserId
};

interface SubscriptionCachePort
{
    public function save(SubscriptionStoreCache $subscriptionStoreCache): void;

    public function get(UserId $userId): Subscription;
}
