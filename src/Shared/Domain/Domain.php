<?php

declare(strict_types=1);

namespace Src\Shared\Domain;

abstract class Domain
{
    protected mixed $events;
    
    public function __construct(
        private readonly mixed $entity = null
    )
    {}

    public function entity(): mixed
    {
        return $this->entity;
    }
}