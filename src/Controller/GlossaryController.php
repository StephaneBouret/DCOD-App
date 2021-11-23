<?php

namespace App\Controller;

use App\Entity\Alphabet;
use App\Entity\IndexWords;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GlossaryController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/index-des-imagiers', name: 'glossary')]
    public function index(): Response
    {
        $alphabet = $this->entityManager->getRepository(Alphabet::class)->findAllByAsc();

        return $this->render('glossary/index.html.twig', [
            'alphabet' => $alphabet
        ]);
    }

    #[Route('/index-des-imagiers/{slug}', name: 'single_glossary')]
    public function show($slug): Response
    {
        $alphabet = $this->entityManager->getRepository(Alphabet::class)->findAllByAsc();
        $singleLetter = $this->entityManager->getRepository(Alphabet::class)->findOneBy(['slug' => $slug]);
        $detailSingleLetter = $this->entityManager->getRepository(IndexWords::class)->findByAlphabetFieldBySlug($singleLetter);

        if (!$singleLetter) {
            return $this->redirectToRoute('glossary');
        }

        return $this->render('glossary/single.html.twig', [
            'alphabet' => $alphabet,
            'singleLetter' => $singleLetter,
            'detailSingleLetter' => $detailSingleLetter
        ]);
    }
}
