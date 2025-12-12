<?php

declare(strict_types=1);

namespace Src\Shared\Domain\ValueObjects;

abstract class ValueObject
{
    public function __construct(private readonly mixed $value)
    {}

    public function value(): mixed
    {
        return $this->value;
    }
}