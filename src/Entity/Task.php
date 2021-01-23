<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;


    public function __construct(
        /**
         * @ORM\Column(type="string", length=255)
         */
        private string $title,

        /**
         * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tasks")
         * @ORM\JoinColumn(nullable=false)
         */
        private User $performedBy,

        /**
         * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="tasks")
         * @ORM\JoinColumn(nullable=false)
         */
        private Project $project,

    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProject(): Project
    {
        return $this->project;
    }


    public function getTitle(): string
    {
        return $this->title;
    }


    public function getPerformedBy(): User
    {
        return $this->performedBy;
    }


}
