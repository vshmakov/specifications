<?php

declare(strict_types=1);

namespace App\Specification;

final class Equals extends Specification
{
    public function __construct(private string $field, private mixed $value)
    {
    }

    public function isSatisfiedBy(object $entity): bool
    {
        return $this->value === $this->getFieldValue($entity, $this->field);
    }

    public function generateDql(string $alias): ?string
    {
        return sprintf('%s.%s = :%2$s', $alias, $this->field);
    }

    public function getParameters(): array
    {
        return [
            $this->field => $this->value,
        ];
    }
}
