<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormateursController extends AbstractController
{
    #[Route('/formateurs', name: 'app_formateurs')]
    public function index(): Response
    {
        return $this->render('formateurs/index.html.twig', [
            'controller_name' => 'FormateursController',
        ]);
    }
}
