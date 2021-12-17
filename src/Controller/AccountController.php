<?php

namespace App\Controller;

use DateInterval;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/compte', name: 'account')]
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());
        $userActive = $this->getUser();
        $createdAt = $userActive->getCreatedAt();
        $isValid = $userActive->getIsValid();
        $role = $userActive->getRoles();
        $date1Year = clone $createdAt;
        $date1Year->add(new DateInterval('P1Y'));
        
        return $this->render('account/index.html.twig', [
            'orders' => $orders,
            "createdAt" => $createdAt,
            'isValid' => $isValid,
            'role' => $role,
            'date1Year' => $date1Year
        ]);
    }
}
