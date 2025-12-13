<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class SubscriptionStoreCache extends ValueObject
{
    public function redisKey(): string
    {
        return "user:" . $this->value()["user_id"] . ":subscription";
    }

    public function handler(): string
    {
        return json_encode([
            "plan_id" => $this->value()["plan_id"],
            "expires_at" => $this->value()["expires_at"]
        ]);
    }

    public function ttl(): int
    {
        return 60 * 60;
    }
}