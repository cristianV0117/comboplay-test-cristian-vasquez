<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class SubscriptionCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly int $userId,
        public readonly int $planId,
        public readonly string $expiresAt
    ) {}
}

