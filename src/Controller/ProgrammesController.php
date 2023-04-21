<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgrammesController extends AbstractController
{
    #[Route('/programmes', name: 'app_programmes')]
    public function index(): Response
    {
        return $this->render('programmes/index.html.twig', [
            'controller_name' => 'ProgrammesController',
        ]);
    }
}
