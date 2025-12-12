<?php

use Illuminate\Support\Facades\Route;
use Src\SubscriptionsContext\Subscription\Infrastructure\Controllers\{
    SubscriptionStoreController,
    SubscriptionStatusController
};

Route::post('/subscriptions', SubscriptionStoreController::class);
Route::get('/subscriptions/{userId}/status', SubscriptionStatusController::class);

