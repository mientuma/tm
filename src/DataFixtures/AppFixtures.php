<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadUser($manager);
    }

    private function loadUser(ObjectManager $manager)
    {
        $user = new User();
        $user->setPassword('$2y$13$mfPppxl.x5cg6YY9.fE8ZeABQzOzNOPRB89/niZ5GQggwewW7p8bO');
        $user->setUsername('test');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
    }

}
