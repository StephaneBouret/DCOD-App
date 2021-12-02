<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\User;
use App\Entity\Order;
use Stripe\StripeClient;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference): Response
    {
        $session = $this->requestStack->getSession();
        $user = $session->get('user');
        Stripe::setApiKey('sk_test_51K1uk1GfT2FwntXckSmSg67n6TIEgmnVvymyHM3mvHtfDKvwcWSXzJVARHpvLhJgLX4ce7VpU2BUwQ1mY6nL7DKC00w0AwHBCb');
        
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneBy(array('reference' => $reference));
        
        if (!$order) {
            new JsonResponse(['error' => 'order']);
        }

        foreach ($cart->getfull() as $product) {
            $stripe = new StripeClient('sk_test_51K1uk1GfT2FwntXckSmSg67n6TIEgmnVvymyHM3mvHtfDKvwcWSXzJVARHpvLhJgLX4ce7VpU2BUwQ1mY6nL7DKC00w0AwHBCb');
            $stripe->products->create(['name' => $product['product']->getName()]);
            $idPrice = $stripe->products->create(['name' => $product['product']->getName()])->id;
            
            $price = \Stripe\Price::create([
                'product' =>  $idPrice,
                'unit_amount' => $product['product']->getPrice(),
                'currency' => 'eur',
            ]);

            $productsForStripe[] = [
                'price' => $price,
                'quantity' => $product['quantity'],
            ];
        }

        $userEmail = $entityManager->getRepository(User::class)->findOneById($user->getId());

        $checkout_session = Session::create([
            'customer_email' => $userEmail->getEmail(),
            'line_items' => [
                $productsForStripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();


        return $this->redirect($checkout_session->url);
    }
}
