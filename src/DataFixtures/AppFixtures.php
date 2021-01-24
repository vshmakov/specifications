<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $objectManager): void
    {
        $admin = new User('admin', [User::ROLE_ADMIN]);
        $manager = new User('manager', [User::ROLE_MANAGER]);
        $developer = new User('developer', [User::ROLE_DEVELOPER]);

        foreach ([$admin, $manager, $developer] as $entity) {
            $objectManager->persist($entity);
        }

        $objectManager->flush();
    }
}
