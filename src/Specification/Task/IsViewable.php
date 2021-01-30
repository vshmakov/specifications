<?php

declare(strict_types=1);

namespace App\Specification\Task;

use App\Entity\User;
use App\Security\CurrentUserProvider;
use App\Specification\AlwaysSpecified;
use App\Specification\CompositeSpecification;
use App\Specification\Equals;
use App\Specification\Join;
use App\Specification\MemberOf;
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

        $user = $this->currentUserProvider->getUser();

        if ($this->authorizationChecker->isGranted(User::ROLE_MANAGER)) {
            $isProjectMember = new MemberOf('members', $user);

            return new Join('task', 'project', $isProjectMember);
        }

        return new Equals('performedBy', $user);
    }
}
