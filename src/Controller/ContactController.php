<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType:: class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $content = "Bonjour,<br/> Vous venez de recevoir un message de la part de " . ucwords($data['prenom']) . ' ' . mb_strtoupper($data['nom']) . "<br/><br/>";
            $content .= "Email : " . $data['email'] . "<br/>";
            $content .= "Sujet : " . $data['content'];
            $mail = new Mail();
            $mail->send('contact@discommentondit.fr', 'Administrateur', 'Demande de contact sur l\'application Dis, comment on dit', $content);
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
