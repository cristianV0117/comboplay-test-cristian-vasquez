<?php

declare(strict_types=1);

namespace Src\SubscriptionsContext\Subscription\Infrastructure\Jobs\Laravel;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final class SendSubscriptionEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public readonly int $userId,
        public readonly int $planId,
        public readonly string $expiresAt
    ) {}

    public function handle(): void
    {
        // Simular envío de email (tarea secundaria que no debe penalizar la respuesta)
        Log::info("Sending subscription email", [
            'user_id' => $this->userId,
            'plan_id' => $this->planId,
            'expires_at' => $this->expiresAt,
            'timestamp' => now()->toIso8601String(),
            'action' => 'subscription_email_sent'
        ]);
    }
    public function failed(\Throwable $exception): void
    {
        Log::error("Failed to send subscription email", [
            'user_id' => $this->userId,
            'plan_id' => $this->planId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}

