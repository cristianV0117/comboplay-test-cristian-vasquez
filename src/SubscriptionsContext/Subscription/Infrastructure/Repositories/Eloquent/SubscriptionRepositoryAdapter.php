<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Repositories\Eloquent;

use Src\SubscriptionsContext\Subscription\Domain\Repositories\SubscriptionRepositoryPort;
use Src\SubscriptionsContext\Subscription\Domain\Subscription;
use Src\SubscriptionsContext\Subscription\Domain\ValueObjects\SubscriptionStore;

use App\Models\{
    Subscription as SubscriptionModel,
    Plan
};
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

        $plan = Plan::find($data['plan_id']);

        if (!$plan) {
            throw new SubscriptionStoreFailedException(
                message: "El plan no existe",
                code: 404
            );
        }

        $data['expires_at'] = now()->addDays($plan->duration_days);

        try {
            $subscription = SubscriptionModel::create($data);
            $subscription->load(['user', 'plan']);
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
            entity: [
                'id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'plan_id' => $subscription->plan_id,
                'starts_at' => $subscription->starts_at,
                'expires_at' => $subscription->expires_at,
                'user_name' => $subscription->user->name,
                'plan_name' => $subscription->plan->name,
                'plan_price' => $subscription->plan->price,
                'duration_days' => $subscription->plan->duration_days,
            ],
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