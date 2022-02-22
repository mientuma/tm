<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function createTask(Request $request, ManagerRegistry $doctrine): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
        }

        return $this->renderForm('task/task.html.twig',[
            'form' => $form
        ]);
    }
}
