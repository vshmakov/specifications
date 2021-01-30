<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;

final class CurrentUserProvider
{
    public function __construct(private Security $security)
    {
    }

    public function getUser(): User
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new UnauthorizedHttpException();
        }

        return $user;
    }
}
