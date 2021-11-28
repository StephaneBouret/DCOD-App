<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SupportCategoriesController extends AbstractController
{
    #[Route('/support/categories', name: 'support_categories')]
    public function index(): Response
    {
        return $this->render('support_categories/index.html.twig');
    }

    #[Route('/support/enregistrement', name: 'register_help')]
    public function registerHelp(): Response
    {
        return $this->render('support_categories/register.html.twig');
    }

    #[Route('/support/comment-reinitialiser-mon-mot-de-passe', name: 'resetMyPw')]
    public function resetPW(): Response
    {
        return $this->render('support_categories/resetPw.html.twig');
    }

    #[Route('/support/pourquoi-mon-lien-ne-fonctionne-pas', name: 'whyNoReset')]
    public function whyNoReset(): Response
    {
        return $this->render('support_categories/whyNoReset.html.twig');
    }

    #[Route('/support/me-connecter-a-mon-compte', name: 'myAccount')]
    public function myAccount(): Response
    {
        return $this->render('support_categories/myAccount.html.twig');
    }

    #[Route('/support/se-deconnecter-de-mon-compte', name: 'disconnect')]
    public function disconnectHelp(): Response
    {
        return $this->render('support_categories/disconnect.html.twig');
    }

    #[Route('/support/modifier-mes-parametres-personnels', name: 'changeParameters')]
    public function changeParameters(): Response
    {
        return $this->render('support_categories/changeParameters.html.twig');
    }

    #[Route('/support/changer-mon-adresse-email', name: 'changeEmail')]
    public function changeEmail(): Response
    {
        return $this->render('support_categories/changeEmail.html.twig');
    }

    #[Route('/support/changer-mon-mot-de-passe', name: 'changeMyPw')]
    public function changeMyPw(): Response
    {
        return $this->render('support_categories/changeMyPw.html.twig');
    }

    #[Route('/support/acces-aux-thematiques', name: 'accessTopics')]
    public function accessTopics(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('support_categories/accessTopics.html.twig');
    }
}
