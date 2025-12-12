<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;
use DateTimeImmutable;

final class SubscriptionStore extends ValueObject
{
    const int DEFAULT_EXPIRES_AT = 10;

    public function handler(): array
    {
        return [
            'user_id' => $this->value()['userId'],
            'plan_id' => $this->value()['planId'],
            'starts_at' => new DateTimeImmutable(), 
            'expires_at' => now()->addDays(value: self::DEFAULT_EXPIRES_AT)
        ];
    }
}