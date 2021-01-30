<?php

declare(strict_types=1);

namespace App\Specification\Task;

use App\Entity\User;
use App\Security\CurrentUserProvider;
use App\Specification\AlwaysSpecified;
use App\Specification\AndX;
use App\Specification\CompositeSpecification;
use App\Specification\Equals;
use App\Specification\Join;
use App\Specification\Not;
use App\Specification\Project\IsArchived;
use App\Specification\Specification;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class IsViewable extends CompositeSpecification
{
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker, private CurrentUserProvider $currentUserProvider)
    {
    }

    public function getSpecification(): Specification
    {
        if ($this->authorizationChecker->isGranted(User::ROLE_ADMIN)) {
            return new AlwaysSpecified();
        }

        $isNotArchived = new Not(new IsArchived());
        $user = $this->currentUserProvider->getUser();

        if ($this->authorizationChecker->isGranted(User::ROLE_MANAGER)) {
            $isProjectMember = new Join(
                'project',
                'members',
                new Equals(null, $user)
            );

            return $this->getProjectSpecification(new AndX($isNotArchived, $isProjectMember));
        }

        return new AndX(
            new Equals('performedBy', $user),
            $this->getProjectSpecification($isNotArchived)
        );
    }

    private function getProjectSpecification(Specification $specification): Join
    {
        return new Join('task', 'project', $specification);
    }
}
