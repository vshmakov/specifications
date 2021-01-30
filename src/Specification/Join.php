<?php

declare(strict_types=1);

namespace App\Specification;

use Doctrine\ORM\QueryBuilder;

final class Join extends Specification
{
    public function __construct(private string $rootAlias, private string $field, private Specification $specification)
    {
    }

    public function isSatisfiedBy(object $entity): bool
    {
    }

    public function generateDql(string $alias): ?string
    {
        return $this->specification
            ->generateDql($this->field);
    }

    public function getParameters(string $alias): array
    {
        return $this->specification
            ->getParameters($this->field);
    }

    public function modifyQuery(QueryBuilder $queryBuilder): void
    {
        $queryBuilder->join(sprintf('%s.%s', $this->rootAlias, $this->field), $this->field);
        $this->specification->modifyQuery($queryBuilder);
    }
}
