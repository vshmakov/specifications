<?php

declare(strict_types=1);

namespace App\Specification;

use Doctrine\ORM\QueryBuilder;

abstract class Specification
{
    abstract public function isSatisfiedBy(object $entity): bool;

    abstract public function generateDql(string $alias): ?string;

    abstract public function getParameters(): array;

    public function addFilter(QueryBuilder $queryBuilder): void
    {
        $dql = $this->generateDql($queryBuilder->getRootAliases()[0]);

        if (null === $dql) {
            return;
        }

        $queryBuilder->andWhere($dql);

        foreach ($this->getParameters() as $field => $value) {
            $queryBuilder->setParameter($field, $value);
        }
    }
}
