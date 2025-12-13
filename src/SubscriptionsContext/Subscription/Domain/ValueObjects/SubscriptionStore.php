<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;
use DateTimeImmutable;

final class SubscriptionStore extends ValueObject
{
    public function handler(): array
    {
        return [
            'user_id' => $this->value()['userId'],
            'plan_id' => $this->value()['planId'],
            'starts_at' => new DateTimeImmutable()
        ];
    }
}