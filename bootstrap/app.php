<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Src\SubscriptionsContext\Subscription\Domain\Exceptions\SubscriptionStoreFailedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (SubscriptionStoreFailedException $e, $request) {
            return response()->json([
                'message' => null,
                'error'   => $e->getMessage(),
                'status'  => false,
                'path'    => $request->path(),
            ], $e->getCode() ?: 400);
        });
    })->create();
