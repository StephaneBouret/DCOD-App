<?php

namespace App\Controller;

use DateInterval;
use App\Entity\Order;
use App\Entity\OrderDetails;
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
        // Modification de la méthode pour le calcul de la date de fin d'abonnement
        if (!$orders) {
            $date1Year->add(new DateInterval('P1Y'));
        } else {
            foreach ($orders as $order) {
                $orderId = $order->getId();
                $detailById = $this->entityManager->getRepository(OrderDetails::class)->findOrderDetailsById($orderId);
                $duration = $detailById[0]->getDuration();
                $date1Year->add(new DateInterval('P'.$duration.'M'));
            }
        }
        
        return $this->render('account/index.html.twig', [
            'orders' => $orders,
            "createdAt" => $createdAt,
            'isValid' => $isValid,
            'role' => $role,
            'date1Year' => $date1Year
        ]);
    }
}
