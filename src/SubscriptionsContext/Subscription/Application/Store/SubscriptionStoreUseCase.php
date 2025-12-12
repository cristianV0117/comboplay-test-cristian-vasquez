<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Application\Store;

use Src\SubscriptionsContext\Subscription\Domain\Repositories\SubscriptionRepositoryPort;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\SubscriptionStore;

final class SubscriptionStoreUseCase
{
    public function __construct(
        private readonly SubscriptionRepositoryPort $subscriptionRepositoryPort
    )
    {}

    public function __invoke(
        int $userId,
        int $planId 
    )
    {
        return $this->subscriptionRepositoryPort->store(new SubscriptionStore([
            'userId' => $userId,
            'planId' => $planId
        ]));
    }
}