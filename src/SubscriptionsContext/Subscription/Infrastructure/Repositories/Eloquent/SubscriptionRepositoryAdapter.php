<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Repositories\Eloquent;

use Src\SubscriptionsContext\Subscription\Domain\Repositories\SubscriptionRepositoryPort;
use Src\SubscriptionsContext\Subscription\Domain\Subscription;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\SubscriptionStore;

use App\Models\Subscription as SubscriptionModel;

final class SubscriptionRepositoryAdapter implements SubscriptionRepositoryPort
{
    public function store(SubscriptionStore $store): Subscription
    {
        $store = SubscriptionModel::create(attributes: $store->handler());

        return new Subscription(entity: $store->toArray(), transactionStatus: true);
    }
}