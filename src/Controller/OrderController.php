<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private $entityManager;
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }
   
    #[Route('/commande/recapitulatif', name: 'order_recap')]
    public function add(Cart $cart): Response
    {
        $session = $this->requestStack->getSession();
        $user = $session->get('user');
        $userActive = $this->getUser();
        $date = new \DateTime();

        if ($userActive) {
            return $this->redirectToRoute('products');
        }

        if (!$cart->get()) {
            return $this->redirectToRoute('pricing');
        }

        if(!$user) {
            return $this->redirectToRoute('account_signing');
        }

        $firstname = $user->getFirstname();
        $lastname = $user->getLastname();
        $email = $user->getEmail();
        
        $delivery_content = $firstname . ' ' . $lastname;
        $delivery_content .= '<br/>' . $email;

        // Enregistrer ma commande Order()
        $order = new Order;
        $reference = $date->format('dmY') . '-' . uniqid();
        $order->setReference($reference);
        $userOrder = $this->entityManager->getRepository(User::class)->findOneById($user->getId());
        $order->setUser($userOrder);
        $order->setCreatedAt($date);
        $order->setIsPaid(false);
        
        $this->entityManager->persist($order);

        // Enregistrer mes produits OrderDetails()
        foreach ($cart->getFull() as $product) {
            $orderDetails = new OrderDetails;
            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($product['product']->getName());
            $orderDetails->setQuantity($product['quantity']);
            $orderDetails->setPrice($product['product']->getPrice());
            $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
            $this->entityManager->persist($orderDetails);
        }

        $this->entityManager->flush();

        return $this->render('order/add.html.twig', [
            'cart' => $cart->getFull(),
            'delivery' => $delivery_content,
            'reference' => $order->getReference()
        ]);
    }

    #[Route('/commande/annuler', name: 'order_reset')]
    public function reset(): Response
    {
        $session = $this->requestStack->getSession();
        $userSessionId = $session->get('user')->getId();
        $userActive = $this->getUser();

        if ($userActive) {
            return $this->redirectToRoute('products');
        }

        $userToDeleted = $this->entityManager->getRepository(User::class)->findOneById($userSessionId);
        // $orderToDeleted = $this->entityManager->getRepository(Order::class)->findOrderByUserId($userSessionId);

        $this->entityManager->remove($userToDeleted);
        // $this->entityManager->remove($orderToDeleted);
        $this->entityManager->flush();

        $session->clear();
        return $this->redirectToRoute('cart');
    }
}
