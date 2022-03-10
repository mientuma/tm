<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
//        $users = $manager->getRepository(User::class)->findAll();
//        dump($users);
        $this->loadTasks($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUsersData() as [$name, $password, $roles])
        {
            $user = new User();
            $user->setUsername($name);
            $user->setPassword($password);
            $user->setRoles([$roles]);
            $manager->persist($user);
        }
        $manager->flush();
    }

    private function loadTasks(ObjectManager $manager)
    {
        foreach ($this->getTasksData() as [$title, $description, $authorID, $responsibleWorkerID, $priority])
        {
            $author = $manager->getRepository(User::class)->find($authorID);
            $responsibleWorker = $manager->getRepository(User::class)->find($responsibleWorkerID);
            $task = new Task();
            $task->setTitle($title);
            $task->setDescription($description);
            $task->setAuthor($author);
            $task->setCreationDate();
            $task->setResponsibleWorker($responsibleWorker);
            $task->setPriority($priority);
            $manager->persist($task);
        }
        $manager->flush();
    }

    private function getUsersData(): array
    {
        return [
            ['Adam', '$2y$13$mfPppxl.x5cg6YY9.fE8ZeABQzOzNOPRB89/niZ5GQggwewW7p8bO', 'ROLE_ADMIN', 1],
            ['John', '$2y$13$a87BUCewuZ/361fcP9wAve9F2nxT3KHPnhDE6FhheLxPM5i7wSuHG', 'ROLE_USER', 2],
            ['Alice', '$2y$13$vHD6EnCRxXi2DTa.m1wz/u04yhp3UhK.xm7/KrNwYb97iXv/2JbZW', 'ROLE_USER', 3],
            ['Robert', '$2y$13$w8eTOZ8LELEZwIDcGk0ZT.K59EBpob0ezK6OPtaBKw0jfqWslgSae', 'ROLE_USER', 4]
        ];
    }

    private function getTasksData(): array
    {
        return [
            ['Make invoice', 'Settle the investment', 0, 3, 'High'],
            ['Make estimate', 'Make cost estimate', 0, 3, 'Medium']
        ];
    }
}
