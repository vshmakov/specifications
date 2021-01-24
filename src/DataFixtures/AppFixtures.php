<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $objectManager): void
    {
        $admin = new User(username: 'admin', roles: [User::ROLE_ADMIN]);
        $manager = new User(username: 'manager', roles: [User::ROLE_MANAGER]);
        $developer = new User(username: 'developer', roles: [User::ROLE_DEVELOPER]);

        $actual = new Project(status: Project::ACTUAL_STATUS, members: [$manager]);
        $empty = new Project(status: Project::ACTUAL_STATUS, members: []);
        $archived = new Project(status: Project::ARCHIVED_STATUS, members: [$manager]);

        $developerTask = new Task(title: 'Developer task', performedBy: $developer, project: $actual);
        $managerTask = new Task(title: 'Manager task', performedBy: $manager, project: $actual);
        $emptyTask = new Task(title: 'Empty task', performedBy: $manager, project: $empty);

        foreach ([$admin, $manager, $developer, $actual, $empty, $archived, $developerTask, $managerTask, $emptyTask] as $entity) {
            $objectManager->persist($entity);
        }

        $objectManager->flush();
    }
}
