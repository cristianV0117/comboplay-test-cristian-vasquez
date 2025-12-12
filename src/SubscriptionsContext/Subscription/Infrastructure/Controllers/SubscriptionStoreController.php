<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Controllers;

use Illuminate\Http\Request;
use Src\Shared\Infrastructure\Controllers\CustomController;

final class SubscriptionStoreController extends CustomController
{
    public function __invoke(Request $request): array
    {
        dd($request->toArray());
    }
}