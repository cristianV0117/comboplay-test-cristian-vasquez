<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Repositories\Redis;

use Illuminate\Support\Facades\Redis;
use Src\SubscriptionsContext\Subscription\Domain\Repositories\SubscriptionCachePort;
use Src\SubscriptionsContext\Subscription\Domain\Subscription;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\UserId;

final class RedisSubscriptionCacheAdapter implements SubscriptionCachePort
{
    public function save(int $userId, array $subscriptionData): void
    {
        $ttl = 60 * 5;

        Redis::setex(
            "user:$userId:subscription",
            $ttl,
            json_encode($subscriptionData)
        );
    }

    public function get(UserId $userId): ?Subscription
    {
        $userId = $userId->value();

        $raw = Redis::get("user:$userId:subscription");

        $response =  $raw ? json_decode(json: $raw, associative: true) : null;

        return $response ? new Subscription(entity: $response, transactionStatus: true) : new Subscription(entity: null, transactionStatus: false);
    }

    public function delete(int $userId): void
    {
        Redis::del("user:$userId:subscription");
    }
}