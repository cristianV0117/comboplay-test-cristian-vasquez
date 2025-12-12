<?php

namespace Src\SubscriptionsContext\Subscription\Domain;

use Src\Shared\Domain\Domain;

final class Subscription extends Domain
{

    private int $transactionStatus;

    public function __construct(mixed $entity = null, bool $transactionStatus)
    {
        parent::__construct($entity);

        $this->transactionStatus = $transactionStatus;
    }

    public function transactionStatus(): bool
    {
        return $this->transactionStatus;
    }
}