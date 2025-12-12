<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Repositories\Redis;

use Illuminate\Support\Facades\Redis;
use Src\SubscriptionsContext\Subscription\Domain\Repositories\SubscriptionCachePort;
use Src\SubscriptionsContext\Subscription\Domain\Subscription;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\{
    UserId,
    SubscriptionStoreCache
};

final class RedisSubscriptionCacheAdapter implements SubscriptionCachePort
{
    public function save(SubscriptionStoreCache $subscriptionStoreCache): void
    {
        Redis::setex(
            $subscriptionStoreCache->redisKey(),
            $subscriptionStoreCache->ttl(),
            $subscriptionStoreCache->handler()
        );
    }

    public function get(UserId $userId): Subscription
    {
        $userId = $userId->redisKey();

        $raw = Redis::get($userId);

        $response =  $raw ? json_decode(json: $raw, associative: true) : null;

        return $response ? new Subscription(entity: $response, transactionStatus: true) : new Subscription(entity: null, transactionStatus: false);
    }
}