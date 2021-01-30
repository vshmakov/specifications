<?php

declare(strict_types=1);

namespace App\Specification;

class AlwaysSpecified extends Specification
{
    public function isSatisfiedBy(object $entity): bool
    {
        return true;
    }

    public function generateDql(string $alias): ?string
    {
        return null;
    }

    public function getParameters(): array
    {
        return [];
    }
}
