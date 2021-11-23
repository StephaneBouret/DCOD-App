<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{
    #[Route('/application', name: 'application')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('products');
        }
        return $this->render('application/index.html.twig');
    }
}
