<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SupportUsController extends AbstractController
{
    #[Route('/nous-soutenir', name: 'support_us')]
    public function index(): Response
    {
        return $this->render('support_us/index.html.twig');
    }

    #[Route('/devenir-partenaire', name: 'become_partner')]
    public function showPartner(): Response
    {
        return $this->render('support_us/partner.html.twig');
    }
}
