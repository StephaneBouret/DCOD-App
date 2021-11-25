<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /* INJECTION DE DEPENDANCE*/
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        
        $this->entityManager = $entityManager;
    }

    #[Route('/nous-contacter', name: 'contact')]
    public function index(Request $request): Response
    {
        $newMail = new Contact;

        $form = $this->createForm(ContactType:: class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            // Upload du mail en BDD
            $firstname = ucwords($form->get('prenom')->getData());
            $lastname = mb_strtoupper($form->get('nom')->getData());
            $email = $form->get('email')->getData();
            $subject = $form->get('content')->getData();
            $newMail->setFirstname($firstname);
            $newMail->setLastname($lastname);
            $newMail->setContent($subject);
            $newMail->setEmail($email);
            $newMail->setSendAt(new \DateTime());
            
            $this->entityManager->persist($newMail);
            $this->entityManager->flush();

            // Envoi du mail
            $content = "Bonjour,<br/> Vous venez de recevoir un message de la part de " . ucwords($data['prenom']) . ' ' . mb_strtoupper($data['nom']) . "<br/><br/>";
            $content .= "Email : " . $data['email'] . "<br/>";
            $content .= "Sujet : " . $data['content'];
            $mail = new Mail();
            $mail->send('contact@discommentondit.com', 'Administrateur', 'Demande de contact sur l\'application Dis, comment on dit', $content);
            $this->addFlash('success', 'Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais.');
            unset($form);
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig',
        [
            'form' => $form->createView()
        ]);
    }
}
