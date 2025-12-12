<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Domain\Repositories;

use Src\SubscriptionsContext\Subscription\Domain\Subscription;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\SubscriptionStore;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\UserId;

interface SubscriptionRepositoryPort
{
    public function store(SubscriptionStore $store): Subscription;

    public function getStatusByUser(UserId $userId): Subscription;
}