<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Application\Store;

use Src\SubscriptionsContext\Subscription\Domain\Repositories\{
    SubscriptionRepositoryPort,
    SubscriptionCachePort
};
use Src\SubscriptionsContext\Subscription\Domain\Subscription;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\SubscriptionStore;
use Src\SubscriptionsContext\Subscription\Domain\Events\SubscriptionCreated;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\SubscriptionStoreCache;

final class SubscriptionStoreUseCase
{
    public function __construct(
        private readonly SubscriptionRepositoryPort $subscriptionRepositoryPort,
        private readonly SubscriptionCachePort $cache
    )   
    {}

    public function __invoke(
        int $userId,
        int $planId 
    ): Subscription
    {
        $subscription = $this->subscriptionRepositoryPort->store(store: new SubscriptionStore(value: [
            'userId' => $userId,
            'planId' => $planId
        ]));

        if ($subscription) {
            $this->cache->save(subscriptionStoreCache: new SubscriptionStoreCache(value: [
                'user_id' => $userId,
                'plan_id' => $planId,
                'expires_at' => $subscription->expiresAt()
            ]));

            event(new SubscriptionCreated(
                userId: $userId,
                planId: $planId,
                expiresAt: $subscription->expiresAt()
            ));
        }

        return $subscription;  
    }
}