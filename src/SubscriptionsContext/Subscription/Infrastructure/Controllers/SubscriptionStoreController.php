<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Controllers;

use Illuminate\Http\Request;
use Src\Shared\Infrastructure\Controllers\CustomController;
use Src\SubscriptionsContext\Subscription\Application\Store\SubscriptionStoreUseCase;

final class SubscriptionStoreController extends CustomController
{

    public function __construct(private readonly SubscriptionStoreUseCase $subscriptionStoreUseCase)
    {}

    public function __invoke(Request $request): array
    {
        dd($this->subscriptionStoreUseCase->__invoke(
            $request->get('user_id'),
            $request->get('plan_id')
        ));
    }
}