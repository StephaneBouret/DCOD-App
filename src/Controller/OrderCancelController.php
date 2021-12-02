<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderCancelController extends AbstractController
{
    private $entityManager;
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }
    
    #[Route('/commande/erreur/{stripeSessionId}', name: 'order_cancel')]
    public function index($stripeSessionId): Response
    {
        $userActive = $this->getUser();
        $session = $this->requestStack->getSession();
        $user = $session->get('user');
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        $userInDB = $this->entityManager->getRepository(User::class)->findOneById($user->getId());

        if ($userActive) {
            return $this->redirectToRoute('products');
        }

        if (!$order || $order->getUser() != $userInDB) {
            return $this->redirectToRoute('home');
        }

        return $this->render('order_cancel/index.html.twig', [
            'order' => $order,
        ]);
    }
}
