<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Src\SubscriptionsContext\Subscription\Domain\Repositories\SubscriptionRepositoryPort;
use Src\SubscriptionsContext\Subscription\Infrastructure\Repositories\Eloquent\SubscriptionRepositoryAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            SubscriptionRepositoryPort::class,
            SubscriptionRepositoryAdapter::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
