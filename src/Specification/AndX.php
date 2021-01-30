<?php

declare(strict_types=1);

namespace App\Specification;

use Doctrine\ORM\QueryBuilder;

final class AndX extends Specification
{
    private array $specifications;

    public function __construct(Specification ...$specifications)
    {
        $this->specifications = $specifications;
    }

    public function isSatisfiedBy(object $entity): bool
    {
        foreach ($this->specifications as $specification) {
            if (!$specification->isSatisfiedBy($entity)) {
                return false;
            }
        }

        return true;
    }

    public function generateDql(string $alias): ?string
    {
        $parts = $this->mapSpecifications(fn (Specification $specification): ?string => $specification->generateDql($alias));

        return implode(' and ', $parts);
    }

    public function getParameters(): array
    {
        $parameters = $this->mapSpecifications(fn (Specification $specification): array => $specification->getParameters());

        return array_merge(...$parameters);
    }

    public function modifyQuery(QueryBuilder $queryBuilder): void
    {
        $this->mapSpecifications(fn (Specification $specification) => $specification->modifyQuery($queryBuilder));
    }

    private function mapSpecifications(\Closure $callback): array
    {
        return array_filter(array_map($callback, $this->specifications));
    }
}
