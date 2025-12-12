<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Domain\Repositories;

interface SubscriptionCachePort
{
    public function save(int $userId, array $subscriptionData): void;

    public function get(int $userId): ?array;

    public function delete(int $userId): void;
}
