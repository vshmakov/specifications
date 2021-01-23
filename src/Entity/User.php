<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToMany(targetEntity=Project::class, mappedBy="members")
     */
    private Collection $projects;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="performedBy")
     */
    private Collection $tasks;

    public function __construct(
        /*
         * @ORM\Column(type="string", length=180, unique=true)
         */
        private string $username,
        /*
         * @ORM\Column(type="json")
         */
        private array $roles = []
    )
    {
        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }


    public function getUsername(): string
    {
        return $this->username;
    }


    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): void
    {

    }

    public function getSalt(): void
    {
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->addMember($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            $project->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setPerformedBy($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getPerformedBy() === $this) {
                $task->setPerformedBy(null);
            }
        }

        return $this;
    }


}
