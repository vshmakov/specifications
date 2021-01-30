<?php

declare(strict_types=1);

namespace App\Specification;

use Symfony\Component\PropertyAccess\PropertyAccess;

class Equals extends Specification
{
    /** @var string */
    private $field;

    private $value;

    public function __construct(string $field, $value)
    {
        $this->field = $field;
        $this->value = $value;
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
        return sprintf('%s.%s = :%2$s', $alias, $this->field);
    }

    public function getParameters(): array
    {
        return [
            $this->field => $this->value,
        ];
    }
}
