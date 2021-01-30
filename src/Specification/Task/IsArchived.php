<?php

declare(strict_types=1);

namespace App\Specification\Project;

use App\Entity\Project;
use App\Specification\CompositeSpecification;
use App\Specification\Equals;
use App\Specification\Specification;

final class IsArchived extends CompositeSpecification
{
    public function getSpecification(): Specification
    {
        return new Equals('status', Project::ARCHIVED_STATUS);
    }
}
