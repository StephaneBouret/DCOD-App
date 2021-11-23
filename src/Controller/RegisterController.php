<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Entity\ValidateRegister;
use App\Form\ValidateRegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/inscription', name: 'register')]
    public function index(Request $request): Response
    {
        // $notification = null;
        if ($this->getUser()) {
            return $this->redirectToRoute('account');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if ($user) {
                // 1 : Enregistrer en base la demande de reset_password avec user, token, createdAt.
                $validate_register = new ValidateRegister();
                $validate_register->setUser($user);
                $validate_register->setToken(bin2hex(random_bytes(100)));
                $validate_register->setCreatedAt(new \DateTime());
                $this->entityManager->persist($validate_register);
                $this->entityManager->flush();

                // 2 : Envoyer un email à l'utilisateur avec un lien lui permettant de valider son inscription.
                $assets = $request->getSchemeAndHttpHost();
                $url = $this->generateUrl('validate_registration', [
                    'token' => $validate_register->getToken()
                ]);
                $fullUrl = $assets.$url;

                $content = "Bonjour " . $user->getFirstname() . "<br/>Vous avez demandé à vous enregistrer sur l'application Dis, comment on dit ?.<br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $fullUrl . "'>valider votre inscription</a>.";

                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'Validation de l\'inscription sur l\'application Dis, comment on dit ?', $content);

                $this->addFlash('success', 'Vous allez recevoir dans quelques secondes un mail avec la procédure pour valider votre inscription.');
            } else {
                $this->addFlash('danger', 'L\'adresse que vous avez renseignée ne correspond pas à nos enregistrements. Merci de nous <a href="/">contacter</a>');
            }
        }

       
        return $this->render('register/index.html.twig');
    }

    #[Route('/valider-mon-inscription/{token}', name: 'validate_registration')]
    public function update(Request $request, $token, UserPasswordHasherInterface $encoder)
    {
        $validate_register = $this->entityManager->getRepository(ValidateRegister::class)->findOneByToken($token);

        if (!$validate_register) {
            return $this->redirectToRoute('register');
        }

        // Vérifier si le createdAt = now - 3h
        $now = new \DateTime();
        if ($now > $validate_register->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('warning', 'Votre demande de validation a expiré. Merci de la renouveller.');
            return $this->redirectToRoute('register');
        }

        // Rendre une vue avec mot de passe et confirmez votre mot de passe.
        $form = $this->createForm(ValidateRegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd = $form->get('validate_password')->getData();

            // Encodage des mots de passe
            $password = $encoder->hashPassword($validate_register->getUser(), $new_pwd);
            $validate_register->getUser()->setPassword($password);

            $this->entityManager->persist($validate_register);
            $this->entityManager->flush();

            // Redirection de l'utilisateur vers la page de connexion.
            $this->addFlash('notice', 'Votre compte est activé ! Vous pouvez vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
