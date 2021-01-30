<?php

declare(strict_types=1);

namespace App\Specification;

use Doctrine\ORM\QueryBuilder;

abstract class Specification
{
    abstract public function isSatisfiedBy(object $entity): bool;

    abstract public function generateDql(string $alias): ?string;

    abstract public function getParameters(string $alias): array;

    public function modifyQuery(QueryBuilder $queryBuilder): void
    {
    }

    public function filter(QueryBuilder $queryBuilder): void
    {
        $this->modifyQuery($queryBuilder);
        $alias = $queryBuilder->getRootAliases()[0];
        $dql = $this->generateDql($alias);

        if (null === $dql) {
            return;
        }

        $queryBuilder->where($dql);

        foreach ($this->getParameters($alias) as $field => $value) {
            $queryBuilder->setParameter($field, $value);
        }
    }
}
