<?php

namespace App\Controller;

use App\Entity\School;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/qui-sommes-nous', name: 'about')]
    public function index(): Response
    {
        $schools = $this->entityManager->getRepository(School::class)->findAll();

        return $this->render('about/index.html.twig',
        [
            'schools' => $schools
        ]);
    }
}
