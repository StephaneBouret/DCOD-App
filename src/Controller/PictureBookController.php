<?php

namespace App\Controller;

use App\Entity\PictureBook;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PictureBookController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/imagiers', name: 'picture_book')]
    public function index(): Response
    {
        $pictureBook = $this->entityManager->getRepository(PictureBook::class)->findAll();

        return $this->render('picture_book/index.html.twig', [
            'pictureBook' => $pictureBook
        ]);
    }

    #[Route('/imagiers/{slug}', name: 'single_picture_book')]
    public function show($slug): Response
    {
        $singleBook = $this->entityManager->getRepository(PictureBook::class)->findOneBy(['slug' => $slug]);
        $pictures = $singleBook->getPicture();

        if (!$singleBook) {
            return $this->redirectToRoute('picture_book');
        }

        return $this->render('picture_book/single.html.twig', [
            'singleBook' => $singleBook,
            'pictures' => $pictures
        ]);
    }
}
