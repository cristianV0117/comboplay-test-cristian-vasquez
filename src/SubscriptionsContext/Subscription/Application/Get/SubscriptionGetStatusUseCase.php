<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Application\Get;

use Src\SubscriptionsContext\Subscription\Domain\Repositories\{
    SubscriptionRepositoryPort,
    SubscriptionCachePort
};
use Src\SubscriptionsContext\Subscription\Domain\Subscription;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\{
    UserId,
    SubscriptionStoreCache
};

final class SubscriptionGetStatusUseCase
{
    public function __construct(
        private readonly SubscriptionCachePort $cache,
        private readonly SubscriptionRepositoryPort $repository
    ) {}

    public function __invoke(int $userId): Subscription
    {

        $cached = $this->cache->get(userId: new UserId(value: $userId));

        if ($cached->transactionStatus()) {
            return $cached;
        }

        $subscription = $this->repository->getStatusByUser(userId: new UserId(value: $userId));


        if ($subscription->transactionStatus()) {
            $this->cache->save(subscriptionStoreCache: new SubscriptionStoreCache(value: [
                'user_id' => $userId,
                'plan_id' => $subscription->entity()['plan_id'],
                'expires_at' => $subscription->entity()['expires_at']
            ]));

            return $subscription;
        }
        
        return new Subscription(entity: 'No tiene subscripciones con nosotros', transactionStatus: false);
    }
}