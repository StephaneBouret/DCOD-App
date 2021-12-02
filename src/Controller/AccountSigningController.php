<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\User;
use App\Form\SigningUserType;
use App\Classe\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountSigningController extends AbstractController
{
    private $entityManager;
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }
    
    #[Route('/enregistrer-votre-compte', name: 'account_signing')]
    public function index(Cart $cart, Request $request, PasswordGenerator $passwordGenerator): Response
    {
        $session = $this->requestStack->getSession();
        $userActive = $this->getUser();
        $userSession = $session->get('user');

        $user = new User;
        $form = $this->createForm(SigningUserType::class, $user);
        $form->handleRequest($request);

        if (!$cart->get()) {
            $session->remove('user');
            return $this->redirectToRoute('home');
        }

        if ($userActive) {
            return $this->redirectToRoute('products');
        }

        if ($userSession != null) {
            return $this->redirectToRoute('order_recap');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $search_email = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            
            if (!$search_email) {
                $firstname = ucwords($user->getFirstname());
                $user->setFirstname($firstname);
                $lastname = mb_strtoupper($user->getLastname());
                $user->setLastname($lastname);
                $password = $passwordGenerator->generateRandomStrongPassword(30);
                $user->setPassword($password);
                $valid = false;
                $user->setIsValid($valid);
                $download = 0;
                $user->setDownload($download);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $session->set('user', $user);

                if ($cart->get()) {
                    return $this->redirectToRoute('order_recap');
                } else {
                    return $this->redirectToRoute('pricing');
                }
            } else {
                $this->addFlash('danger', 'L\'email que vous avez renseigné existe déjà.');
            }
        }

        return $this->render('account_signing/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // #[Route('/modifier-votre-compte', name: 'account_editing')]
    // public function edit(Cart $cart, Request $request): Response
    // {
    //     $session = $this->requestStack->getSession();
    //     $userActive = $this->getUser();
    //     $userSession = $session->get('user');

        
    //     if (!$cart->get()) {
    //         $session->remove('user');
    //         return $this->redirectToRoute('home');
    //     }
        
    //     if ($userActive) {
    //         return $this->redirectToRoute('products');
    //     }
        
    //     if ($userSession == null) {
    //         return $this->redirectToRoute('pricing');
    //     }
        
    //     $form = $this->createForm(SigningUserType::class, $userSession);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $user = $form->getData();

    //         $emailUser = $user->getEmail();
    //         $search_email = $this->entityManager->getRepository(User::class)->findIfEmailExists($emailUser);

    //         $firstname = ucwords($form->get('firstname')->getData());
    //         $lastname = mb_strtoupper($form->get('lastname')->getData());
    //         $tel = $form->get('phone_number')->getData();

    //         $user->setFirstname($firstname);
    //         $user->setLastname($lastname);
    //         $user->setPhoneNumber($tel);

    //         $this->entityManager->persist($user);
    //         $this->entityManager->flush();
            
    //         // if (!$search_email) {
    //             // $firstname = ucwords($user->getFirstname());
    //             // $user->setFirstname($firstname);
    //             // $lastname = mb_strtoupper($user->getLastname());
    //             // $user->setLastname($lastname);

    //         //     $this->entityManager->persist($user);
    //         //     $this->entityManager->flush();

    //         //     $session->set('user', $user);

    //         //     if ($cart->get()) {
    //         //         return $this->redirectToRoute('order_recap');
    //         //     } else {
    //         //         return $this->redirectToRoute('pricing');
    //         //     }
    //         // } else {
    //         //     $this->addFlash('danger', 'L\'email que vous avez renseigné existe déjà.');
    //         // }
    //     }

    //     return $this->render('account_signing/index.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }
}