<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'create_task')]
    public function createTask(ManagerRegistry $doctrine): Response
    {
        $entitryManager = $doctrine->getManager();
        $task = new Task();
        $task->setTitle("Test");
        $entitryManager->persist($task);
        $entitryManager->flush();

        return new Response('Saved new task named '.$task->getTitle().' with id '.$task->getId());
    }
}
