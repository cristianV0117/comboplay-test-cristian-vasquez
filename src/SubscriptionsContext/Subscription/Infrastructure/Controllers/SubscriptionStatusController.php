<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Controllers;

use Illuminate\Http\Request;
use Src\Shared\Infrastructure\Controllers\CustomController;
use Src\SubscriptionsContext\Subscription\Application\Get\SubscriptionGetStatusUseCase;
use Src\SubscriptionsContext\Subscription\Infrastructure\Outputs\SubscriptionOutput;
use Exception;

final class SubscriptionStatusController extends CustomController
{
    public function __construct(
        private readonly SubscriptionGetStatusUseCase $statusUseCase,
        private readonly SubscriptionOutput $subscriptionOutput
    ) {}

    public function __invoke(Request $request, int $userId): mixed
    {
        try {
            $data = $this->statusUseCase->__invoke(userId: $userId);

            if ($data->transactionStatus()) {
                $output = $this->subscriptionOutput->output(
                    path: $request->path(),
                    response: $data->subscriptionStatus(),
                    error: null
                );
            } else {
                $output = $this->subscriptionOutput->output(
                    path: $request->path(),
                    response: $data->entity(),
                    error: null
                );
            }

            return response()->json(
                data: $output,
                status: 201
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
