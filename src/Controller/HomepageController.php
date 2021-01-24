<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

final class HomepageController extends AbstractController
{
    public const ROUTE = 'homepage';

    #[Route('/', name: self::ROUTE, methods: ['GET'])]
    public function __inwalk(): RedirectResponse
    {
        return $this->redirectToRoute(TaskController::INDEX);
    }
}
