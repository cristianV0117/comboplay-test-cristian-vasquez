<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Controllers;

use Src\SubscriptionsContext\Subscription\Infrastructure\Requests\SubscriptionStoreRequest;
use Src\Shared\Infrastructure\Controllers\CustomController;
use Src\SubscriptionsContext\Subscription\Application\Store\SubscriptionStoreUseCase;
use Src\SubscriptionsContext\Subscription\Infrastructure\Outputs\SubscriptionOutput;
use Src\SubscriptionsContext\Subscription\Domain\Exceptions\SubscriptionStoreFailedException;
use Exception;

final class SubscriptionStoreController extends CustomController
{
    public function __construct(
        private readonly SubscriptionStoreUseCase $subscriptionStoreUseCase,
        private readonly SubscriptionOutput $subscriptionOutput
    )
    {}

    public function __invoke(SubscriptionStoreRequest $request): mixed
    {
        try {
            $validated = $request->validated();

            $subscription = $this->subscriptionStoreUseCase->__invoke(
                userId: (int) $validated['user_id'],
                planId: (int) $validated['plan_id']
            );
        
            $output = $this->subscriptionOutput->output(
                path: $request->path(),
                response: "¡Se ha suscrito al plan {$subscription->planName()} correctamente!",
                error: null
            );
        
            return response()->json(
                data: $output,
                status: 201
            );
        } catch (SubscriptionStoreFailedException $exception) {
            return response()->json(
                data: $this->subscriptionOutput->output(
                    path: $request->path(),
                    response: null,
                    error: $exception->getMessage()
                ),
                status: $exception->getCode()
            );
        } catch (Exception $exception) {
            return response()->json(
                data: $this->subscriptionOutput->output(
                    path: $request->path(),
                    response: null,
                    error: $exception->getMessage()
                ),
                status: $exception->getCode()
            );
        }
    }
}