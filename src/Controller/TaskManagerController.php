<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskManagerController extends AbstractController
{
    /**
     * @Route ("/", name="tm_index")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        var_dump($user);
        return $this->render('base.html.twig');
    }
}