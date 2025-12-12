<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Repositories\Redis;

use Illuminate\Support\Facades\Redis;
use Src\SubscriptionsContext\Subscription\Domain\Repositories\SubscriptionCachePort;

final class RedisSubscriptionCacheAdapter implements SubscriptionCachePort
{
    public function save(int $userId, array $subscriptionData): void
    {
        Redis::set("user:$userId:subscription", json_encode($subscriptionData));
    }

    public function get(int $userId): ?array
    {
        $raw = Redis::get("user:$userId:subscription");

        return $raw ? json_decode($raw, true) : null;
    }

    public function delete(int $userId): void
    {
        Redis::del("user:$userId:subscription");
    }
}