<?php

declare(strict_types=1);

namespace App\Specification;

use Symfony\Component\PropertyAccess\PropertyAccess;

class Equals extends Specification
{
    public function __construct(private ?string $field, private mixed $value)
    {
    }

    public function isSatisfiedBy(object $entity): bool
    {
        return $this->value === PropertyAccess::createPropertyAccessorBuilder()
                ->enableExceptionOnInvalidIndex()
                ->getPropertyAccessor()
                ->getValue($entity, $this->field);
    }

    public function generateDql(string $alias): ?string
    {
        if (null === $this->field) {
            return sprintf('%s = :%1$s', $alias);
        }

        return sprintf('%s.%s = :%2$s', $alias, $this->field);
    }

    public function getParameters(string $alias): array
    {
        return [
            $this->field ?? $alias => $this->value,
        ];
    }
}
