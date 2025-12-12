<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Outputs;

abstract class Output
{
    protected function outputParent(
        mixed $response,
        mixed $error,
        string $path
    ): array
    {
        return [
            "message" => $response,
            "error" => $error,
            "path" => $path
        ];
    }
}