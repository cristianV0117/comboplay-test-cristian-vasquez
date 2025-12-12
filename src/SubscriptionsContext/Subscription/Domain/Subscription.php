<?php

namespace Src\SubscriptionsContext\Subscription\Domain;

use Src\Shared\Domain\Domain;

final class Subscription extends Domain
{

    private int $transactionStatus;

    public function __construct(mixed $entity = null, bool $transactionStatus)
    {
        parent::__construct(entity: $entity);

        $this->transactionStatus = $transactionStatus;
    }

    public function transactionStatus(): bool
    {
        return $this->transactionStatus;
    }

    public function userId(): int
    {
        return $this->entity()["user_id"];
    }

    public function planId(): int
    {
        return $this->entity()["plan_id"];
    }

    public function status(): string
    {
        return $this->entity()["status"];
    }

    public function expiresAt(): string
    {
        return $this->entity()["expires_at"];
    }
}