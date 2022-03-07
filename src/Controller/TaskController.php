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
        dump($user);
        return $this->render('base.html.twig');
    }

    #[Route('/task/create', name: 'create_task')]
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

        return $this->renderForm('task/create_task.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/task/{id}', name: 'show_task')]
    public function showTask(ManagerRegistry $doctrine, int $id): Response
    {
        $task = $doctrine->getRepository(Task::class)->find($id);
        if ($task){
            return $this->render('task/task.html.twig',
                ['task' => $task]
            );
        }
        else {
            throw $this->createNotFoundException(
                'No task found for id '.$id
            );
        }
    }

    #[Route('/task/list', name: 'show_tasks')]
    public function listTasks(ManagerRegistry $doctrine): Response
    {
        $tasks = $doctrine->getRepository(Task::class)->findAll();
        dump($tasks);
        return $this->render('task/tasks_list.html.twig',
            ['tasks' => $tasks]
        );
    }
}
