<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Domain\Exceptions;

use Exception;

final class SubscriptionStoreFailedException extends Exception
{
    public function __construct(
        string $message = "No se pudo guardar la subscripción",
        int $code = 0,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}