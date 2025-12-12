<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Repositories\Eloquent;

use Src\SubscriptionsContext\Subscription\Domain\Repositories\SubscriptionRepositoryPort;
use Src\SubscriptionsContext\Subscription\Domain\Subscription;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\SubscriptionStore;

use App\Models\Subscription as SubscriptionModel;
use Src\SubscriptionsContext\Subscription\Domain\Exceptions\SubscriptionStoreFailedException;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\UserId;

final class SubscriptionRepositoryAdapter implements SubscriptionRepositoryPort
{
    public function store(SubscriptionStore $store): Subscription
    {
        $data = $store->handler();

        $exists = SubscriptionModel::where('user_id', $data['user_id'])
            ->where('expires_at', '>', now())
            ->exists();

        if ($exists) {
            throw new SubscriptionStoreFailedException(
                message: "El usuario ya tiene una suscripción activa",
                code: 409
            );
        }

        try {
            $subscription = SubscriptionModel::create($data);
        } catch (\Throwable $e) {
            throw new SubscriptionStoreFailedException(
                message: "Ha ocurrido un error guardando la suscripción",
                code: 500,
                previous: $e
            );
        }

        if (!$subscription) {
            throw new SubscriptionStoreFailedException(
                message: "No se pudo guardar la suscripción",
                code: 500
            );
        }

        return new Subscription(
            entity: $subscription->toArray(),
            transactionStatus: true
        );
    }


    public function getStatusByUser(UserId $userId): Subscription
    {
        $row = SubscriptionModel::where('user_id', $userId->value())
        ->orderBy('expires_at', 'desc')
        ->first();

        if (!$row) {
            return new Subscription(entity: null, transactionStatus: false);
        }

        return new Subscription(entity: $row->toArray(), transactionStatus: true);
    }
}