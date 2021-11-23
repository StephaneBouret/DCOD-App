<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ChangeEmail;
use App\Form\UpdateUserType;
use App\Form\ChangeEmailType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountEditController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/compte/edit', name: 'account_edit')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdateUserType::class, $user);
        $form2 = $this->createForm(ChangeEmailType::class, $user);

        $form->handleRequest($request);
        $form2->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $firstname = ucwords($form->get('firstname')->getData());
            $lastname = mb_strtoupper($form->get('lastname')->getData());
            $tel = $form->get('phone_number')->getData();

            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setPhoneNumber($tel);

            $this->entityManager->flush();

            $this->addFlash('success', 'Vos modifications ont bien été prises en compte.');
            return $this->redirectToRoute('account_edit');
        }

        if ($form2->isSubmitted() && $form2->isValid()) {
            $actual_password = $form2->get('password_for_email')->getData();
            $new_email = $form2->get('new_email')->getData();

            $user_found = $this->entityManager->getRepository(User::class)->findOneByEmail($new_email);
            if ($user_found) {
                $this->addFlash('danger', 'Cet email existe déjà. Merci d\'en choisir un autre');
                return $this->redirectToRoute('account_edit');
            }

            if ($encoder->isPasswordValid($user, $actual_password)) {
                $change_email = new ChangeEmail();
                $change_email->setUser($user);
                $change_email->setToken(bin2hex(random_bytes(100)));
                $change_email->setCreatedAt(new \DateTime());
                $this->entityManager->persist($change_email);
                $this->entityManager->flush();

                $user->setEmail($new_email);
                $this->entityManager->persist($user);
                $this->entityManager->flush();

    
                $this->addFlash('success', 'Votre email a bien été modifié. Il vous sert de nouvel identifiant pour vous connecter');
                return $this->redirectToRoute('account_edit');
            } else {
                $this->addFlash('danger', 'Votre mot de passe n\'est pas le bon');
            }
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView(),
            'form2' => $form2->createView()
        ]);
    }
}
