<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Controllers;

use Illuminate\Http\Request;
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

    public function __invoke(Request $request): mixed
    {
        try {

            $this->subscriptionStoreUseCase->__invoke(
                userId: $request->get('user_id'),
                planId: $request->get('plan_id')
            );
        
            $output = $this->subscriptionOutput->output(
                path: $request->path(),
                response: "Se ha guardado la subscripción",
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