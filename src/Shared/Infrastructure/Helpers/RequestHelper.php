<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Helpers;

trait RequestHelper
{
    public function formatErrorsRequest(array $validators): string
    {
        $message = '';
        array_walk($validators, static function ($value) use (&$message) {
            $message .= $value . '|';
        });
        return substr($message, 0, -1);
    }
}