<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Security\CurrentUserProvider;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/task')]
final class TaskController extends AbstractController
{
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker, private CurrentUserProvider $currentUserProvider)
    {
    }

    #[Route('/', name: 'task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        $queryBuilder = $taskRepository->createQueryBuilder('t');
        $this->filter($queryBuilder);

        return $this->render('task/index.html.twig', [
            'tasks' => $queryBuilder->getQuery()
                ->getResult(),
        ]);
    }

    private function filter(QueryBuilder $queryBuilder): void
    {
        if ($this->authorizationChecker->isGranted(User::ROLE_ADMIN)) {
            return;
        }

        $user = $this->currentUserProvider->getUser();

        if ($this->authorizationChecker->isGranted(User::ROLE_MANAGER)) {
            $queryBuilder->andWhere('t.project in(:projects)')
                ->setParameter('projects', $user->getProjects());

            return;
        }

        $queryBuilder->andWhere('t.performedBy = :performedBy')
            ->setParameter('performedBy', $user);
    }

    #[Route('/{id}', name: 'task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        if (!$this->isViewable($task)) {
            throw new AccessDeniedHttpException();
        }

        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    private function isViewable(Task $task): bool
    {
        if ($this->authorizationChecker->isGranted(User::ROLE_ADMIN)) {
            return true;
        }

        $user = $this->currentUserProvider->getUser();

        if ($this->authorizationChecker->isGranted(User::ROLE_MANAGER)) {
            return $user->getProjects()
                ->contains($task->getProject());
        }

        return $task->getPerformedBy() === $user;
    }
}
