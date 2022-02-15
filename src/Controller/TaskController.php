<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{

    #[Route('/', name: 'tm_index')]
    public function index(): Response
    {
        $user = $this->getUser();
        var_dump($user);
        return $this->render('base.html.twig');
    }

    #[Route('/task', name: 'create_task')]
    public function createTask(ManagerRegistry $doctrine): Response
    {
        $entitryManager = $doctrine->getManager();
        $task = new Task();
        $task->setTitle("Test");
        $task->setDescription("Test description");
        $user = $this->getUser();
        $task->setAuthor($user);
        $task->setCreationDate();
        $task->setPriority("Medium");
        $entitryManager->persist($task);
        $entitryManager->flush();

        return new Response('Saved new task named '.$task->getTitle().' with id '.$task->getId());
    }
}
