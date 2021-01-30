<?php

declare(strict_types=1);

namespace App\Specification;

use Doctrine\Common\Collections\Collection;

final class MemberOf extends Specification
{
    public function __construct(private string $field, private object $value)
    {
    }

    /**
     * @param Collection $entity
     */
    public function isSatisfiedBy(object $entity): bool
    {
        return $this->getFieldValue($entity, $this->field)
            ->contains($this->value);
    }

    public function generateDql(string $alias): ?string
    {
        return sprintf(':%2$s member of %1$s.%2$s', $alias, $this->field);
    }

    public function getParameters(): array
    {
        return [
            $this->field => $this->value,
        ];
    }
}
