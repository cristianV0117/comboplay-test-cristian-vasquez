<?php

namespace Src\SubscriptionsContext\Subscription\Domain;

use Illuminate\Support\Carbon;
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

    public function subscriptionStatus()
    {
        if (!$this->ensureIsActive()) {
            return [
                "active" => "Su subscripcion esta inactiva",
                "plan_id" => $this->entity()["plan_id"],
                "expires_at" => $this->entity()["expires_at"],
            ];
        } else {
            return [
                "active" => "Su subscripcion esta activa",
                "plan_id" => $this->entity()["plan_id"],
                "expires_at" => $this->entity()["expires_at"],
            ];
        }
    }

    private  function ensureIsActive(): bool
    {
        $expiresAt = Carbon::parse($this->entity()["expires_at"]);

        return $expiresAt->isFuture();
    }
}