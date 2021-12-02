<?php

namespace App\Controller;

use App\Entity\Plan;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PricingController extends AbstractController
{
    /* INJECTION DE DEPENDANCE*/
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/tarifs', name: 'pricing')]
    public function index(): Response
    {
        $data = $this->entityManager->getRepository(Plan::class)->findAll();
        $dateNow = new \DateTime();

        return $this->render('pricing/index.html.twig', [
            'data' => $data,
            'dateNow' => $dateNow
        ]);
    }
}
