<?php

declare(strict_types=1);

namespace App\Specification;

use Symfony\Component\PropertyAccess\PropertyAccess;

final class In extends Specification
{
    public function __construct(private string $field, private iterable $items)
    {
    }

    public function isSatisfiedBy(object $entity): bool
    {
        $value = $this->getFieldValue($entity);

        foreach ($this->items as $item) {
            if ($item === $value) {
                return true;
            }
        }

        return false;
    }

    public function generateDql(string $alias): ?string
    {
        return sprintf('%s.%s in(:%2$s)', $alias, $this->field);
    }

    public function getParameters(): array
    {
        return [
            $this->field => $this->items,
        ];
    }

    protected function getFieldValue(object $entity): mixed
    {
        return PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor()
            ->getValue($entity, $this->field);
    }
}
