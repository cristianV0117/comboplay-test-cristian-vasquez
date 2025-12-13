<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Domain;

use Illuminate\Support\Carbon;
use Src\Shared\Domain\Domain;

final class Subscription extends Domain
{
    private bool $transactionStatus;

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
        return Carbon::parse($this->entity()["expires_at"])->toISOString();
    }

    public function userName(): string
    {
        return $this->entity()["user_name"];
    }

    public function planName(): string
    {
        return $this->entity()["plan_name"];
    }

    public function durationDays(): int
    {
        return $this->entity()["duration_days"];
    }

    public function subscriptionStatus()
    {
        $response = [
            "plan_id" => $this->entity()["plan_id"],
            "expires_at" => $this->entity()["expires_at"]
        ];

        if (!$this->ensureIsActive()) {
            $response["active"] = "Su subscripcion no está activa";
        } else {
            $response["active"] = "Su subscripcion está activa";
        }

        return $response;
    }

    private  function ensureIsActive(): bool
    {
        $expiresAt = Carbon::parse($this->entity()["expires_at"]);

        return $expiresAt->isFuture();
    }
}