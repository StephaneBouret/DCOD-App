<?php

namespace App\Controller;

use App\Entity\Header;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('products');
        }

        $headers = $this->entityManager->getRepository(Header::class)->findAll();
        
        return $this->render('home/index.html.twig',
        [
            'headers' => $headers
        ]);
    }

    #[Route('/mentions-legales', name: 'legal-notices')]
    public function legal(): Response
    {      
        return $this->render('home/legal.html.twig');
    }

    #[Route('/politique-de-confidentialite', name: 'prevacy-policy')]
    public function privacy(): Response
    {      
        return $this->render('home/prevacy.html.twig');
    }
}
