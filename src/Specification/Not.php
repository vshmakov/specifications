<?php

declare(strict_types=1);

namespace App\Specification;

final class Not extends Specification
{
    public function __construct(private Specification $specification)
    {
    }

    public function isSatisfiedBy(object $entity): bool
    {
        return !$this->specification
            ->isSatisfiedBy($entity);
    }

    public function generateDql(string $alias): ?string
    {
        return sprintf('not (%s)', $this->specification->generateDql($alias));
    }

    public function getParameters(): array
    {
        return $this->specification
            ->getParameters();
    }
}
