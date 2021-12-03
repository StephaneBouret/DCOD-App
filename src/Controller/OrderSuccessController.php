<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\User;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private $entityManager;
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }
    
    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate')]
    public function index(Request $request, Cart $cart, $stripeSessionId): Response
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

        if ($order->getIsPaid() == false) {
            // Modifier le statut isPaid de notre commande en mettant true
            $order->setIsPaid(true);
            $userInDB->setIsValid(true);
            $this->entityManager->flush();

            // Vider toutes les sessions
            $cart->remove();
            $cart->removeUser();

            // Envoyer un email à notre client pour lui confirmer sa commande
            $assets = $request->getSchemeAndHttpHost();
            $url = $this->generateUrl('register');
            $urlBilling = $this->generateUrl('account_order');
            $fullUrl = $assets.$url;
            $fullUrlBilling = $assets.$urlBilling;
            $mail = new Mail();
            $content = "<h3>Nous avons reçu votre paiement.<br/>Merci !</h3><br/>Bonjour " . $order->getUser()->getFirstname() . "<br/>Merci pour votre commande !<br><br/>Toute l'équipe de Dis, comment on dit ? vous souhaite la bienvenue ! Prêt à commencer ? Il vous suffit de cliquer sur le lien ci-dessous.<br><br/>";
            $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $fullUrl . "'>valider votre compte</a>.<br/>";
            $content .= "N'oubliez pas d'utiliser <strong>la même adresse email</strong> pour vous enregistrer !<br/>";
            $content .= "Pour voir votre paiement, imprimer votre reçu, il vout suffit de visiter votre <a href='" . $fullUrlBilling . "'>espace de facturation</a>.";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande de Dis, comment on dit ? est bien validée.', $content);
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
