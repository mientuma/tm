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
        return $this->render('task/index.html.twig');
    }

    #[Route('/task/create', name: 'create_task')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $entityManager = $doctrine->getManager();
            dump($task);
            $entityManager->persist($task);
            $entityManager->flush();
        }

        return $this->renderForm('task/create_task.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/task/{id}', name: 'show_task')]
    public function show(ManagerRegistry $doctrine, int $id): Response
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

    #[Route('/tasks/list/{page}', name: 'list_tasks', defaults: ['page' => 1])]
    public function list(ManagerRegistry $doctrine, $page, Request $request): Response
    {
        $tasks = $doctrine->getRepository(Task::class)->findAllPaginated($page, $request->get('sortby'));
        return $this->render('task/tasks_list.html.twig',
            ['tasks' => $tasks]
        );
    }

    #[Route('/task/edit/{id}', name: 'edit_task')]
    public function edit(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task){
            throw $this->createNotFoundException(
                'No task found for id '.$id
            );
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('show_task', [
                'id' => $id
            ]);
        }
        return $this->renderForm('task/edit_task.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/task/delete/{id}', name: 'delete_task')]
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task){
            throw $this->createNotFoundException(
                'No task found for id '.$id
            );
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('show_tasks');
    }

    #[Route('/tasks/search/{page}', name: 'search_tasks', defaults: ['page' => 1], methods: 'GET')]
    public function search(ManagerRegistry $doctrine, $page, Request $request): Response
    {
        $tasks = null;
        $query = null;

        if ($query = $request->get('query'))
        {
            $tasks = $doctrine->getRepository(Task::class)->
            findByTitle($query, $page, $request->get('sortby'));

            if (!$tasks->getItems()) $tasks = null;
        }
        return $this->render('task/search.html.twig',[
            'tasks' => $tasks,
            'query' => $query
        ]);
    }

}
