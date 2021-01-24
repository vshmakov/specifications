<?php

declare(strict_types=1);

namespace App\Security;

use App\Controller\HomepageController;
use App\Controller\SecurityController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

final class LoginAuthenticator extends AbstractFormLoginAuthenticator
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function supports(Request $request): bool
    {
        return SecurityController::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request): array
    {
        return [
            'username' => $request->request->get('username'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): User
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => $credentials['username']]);

        if (null === $user) {
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): RedirectResponse
    {
        return new RedirectResponse(
            $this->urlGenerator->generate(HomepageController::ROUTE)
        );
    }

    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate(SecurityController::LOGIN_ROUTE);
    }
}
