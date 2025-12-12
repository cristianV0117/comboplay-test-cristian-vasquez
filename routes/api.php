<?php

use Illuminate\Support\Facades\Route;
use Src\SubscriptionsContext\Subscription\Infrastructure\Controllers\SubscriptionStoreController;

Route::post('/subscriptions', SubscriptionStoreController::class);
