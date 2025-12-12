<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Outputs;

use Src\Shared\Infrastructure\Outputs\Output;

final class SubscriptionOutput extends Output
{
    public function Output(string $path, mixed $response, mixed $error): array
    {
        return $this->outputParent(
            response: $response,
            error: $error,
            path: $path
        );
    }
}