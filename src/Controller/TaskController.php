<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Specification\Task\IsViewable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task')]
final class TaskController extends AbstractController
{
    public function __construct(private IsViewable $isViewable)
    {
    }

    #[Route('/', name: 'task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        $queryBuilder = $taskRepository->createQueryBuilder('t');
        $this->isViewable->addFilter($queryBuilder);

        return $this->render('task/index.html.twig', [
            'tasks' => $queryBuilder->getQuery()
                ->getResult(),
        ]);
    }

    #[Route('/{id}', name: 'task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        if (!$this->isViewable->isSatisfiedBy($task)) {
            throw new AccessDeniedHttpException();
        }

        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }
}
