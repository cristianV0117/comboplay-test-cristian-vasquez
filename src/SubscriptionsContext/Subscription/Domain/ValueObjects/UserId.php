<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class UserId extends ValueObject
{
    public function redisKey(): string
    {
        return "user:" . $this->value() . ":subscription";
    }
}