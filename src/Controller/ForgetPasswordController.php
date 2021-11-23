<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Entity\ForgetPassword;
use App\Form\ForgetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ForgetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
   
    #[Route('/mot-de-passe-oublie', name: 'forget_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('account');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if ($user) {
                $accountValidated = $user->getIsValid();

                if ($accountValidated !== true) {
                    $this->addFlash('notice', 'Votre compte n\'est plus actif. Veuillez contacter l\'administrateur');
                    return $this->redirectToRoute('app_login');
                }
                
                // 1 : Enregistrer en base la demande de reset_password avec user, token, createdAt.
                $forget_password = new ForgetPassword();
                $forget_password->setUser($user);
                $forget_password->setToken(bin2hex(random_bytes(100)));
                $forget_password->setCreatedAt(new \DateTime());
                $this->entityManager->persist($forget_password);
                $this->entityManager->flush();

                // 2 : Envoyer un email à l'utilisateur avec un lien lui permettant de mettre à jour son mot de passe.
                $hostname = $request->getSchemeAndHttpHost();
                $url = $this->generateUrl('update_password', [
                    'token' => $forget_password->getToken()
                ]);
                $link = $hostname.$url;

                $content = "Bonjour " . $user->getFirstname() . "<br/>Vous avez demandé à réinitialiser votre mot de passe sur l'application Dis, comment on dit ?.<br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $link . "'>mettre à jour votre mot de passe</a>.";

                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'Réinitialiser votre mot de passe sur l\'application Dis, comment on dit ?', $content);

                $this->addFlash('notice', 'Vous allez recevoir dans quelques secondes un mail avec la procédure pour réinitialiser votre mot de passe.');
            } else {
                $this->addFlash('notice', 'Cette adresse email est inconnue.');
            }
            

        }

        return $this->render('forget_password/index.html.twig');
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'update_password')]
    public function update(Request $request, $token, UserPasswordHasherInterface $encoder)
    {
        $forget_password = $this->entityManager->getRepository(ForgetPassword::class)->findOneByToken($token);

        if (!$forget_password) {
            return $this->redirectToRoute('forget_password');
        }

        // Vérifier si le createdAt = now - 3h
        $now = new \DateTime();
        if ($now > $forget_password->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('notice', 'Votre demande de mot de passe a expiré. Merci de la renouveller.');
            return $this->redirectToRoute('forget_password');
        }

        // Rendre une vue avec mot de passe et confirmez votre mot de passe.
        $form = $this->createForm(ForgetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd = $form->get('new_password')->getData();

            // Encodage des mots de passe
            $password = $encoder->hashPassword($forget_password->getUser(), $new_pwd);
            $forget_password->getUser()->setPassword($password);

            // Flush en base de donnée.
            $this->entityManager->flush();

            // Redirection de l'utilisateur vers la page de connexion.
            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('forget_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
