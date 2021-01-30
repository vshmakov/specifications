<?php

declare(strict_types=1);

namespace App\Specification;

use Doctrine\ORM\QueryBuilder;

abstract class CompositeSpecification extends Specification
{
    abstract public function getSpecification(): Specification;

    public function isSatisfiedBy(object $entity): bool
    {
        return $this->getSpecification()
            ->isSatisfiedBy($entity);
    }

    public function generateDql(string $alias): ?string
    {
        return $this->getSpecification()
            ->generateDql($alias);
    }

    public function getParameters(): array
    {
        return $this->getSpecification()
            ->getParameters();
    }

    public function modifyQuery(QueryBuilder $queryBuilder): void
    {
        $this->getSpecification()
            ->modifyQuery($queryBuilder);
    }
}
