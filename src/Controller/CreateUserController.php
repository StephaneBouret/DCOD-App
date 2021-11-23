<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\CreateUserType;
use App\Classe\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateUserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/creation-utilisateur', name: 'create_user')]
    public function index(Request $request, PasswordGenerator $passwordGenerator): Response
    {
        $user = new User;
        $form = $this->createForm(CreateUserType::class, $user);
        $form->handleRequest($request);

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
                $valid = true;
                $user->setIsValid($valid);
                $download = 0;
                $user->setDownload($download);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $assets = $request->getSchemeAndHttpHost();
                $url = $this->generateUrl('register');
                $fullUrl = $assets.$url;

                $mail = new Mail();
                $content = "Bonjour " . $user->getFirstname() . "<br/>Bienvenue sur l'application de Dis, comment on dit ?.<br><br/>Vous avez la possibilité d'exploiter toutes les ressources<br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $fullUrl . "'>valider votre compte</a>.<br/>";
                $content .= "N'oubliez pas d'utiliser la même adresse email pour vous enregistrer !";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur l\'application de Dis, comment on dit ?', $content);
                $this->addFlash('success', 'L\'enregistrement de l\'utilisateur s\'est correctement déroulée. Un email lui a été adressé.');
                return $this->redirectToRoute('account');
            } else {
                $this->addFlash('danger', 'L\'email que vous avez renseigné existe déjà.');
            }
        }
        
        return $this->render('create_user/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
