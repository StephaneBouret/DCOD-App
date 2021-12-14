<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\GameFiles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameFilesController extends AbstractController
{
    /* INJECTION DE DEPENDANCE*/
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/jeux-de-langage', name: 'game_files')]
    public function index(): Response
    {
        $games = $this->entityManager->getRepository(GameFiles::class)->findAll();

        return $this->render('game_files/index.html.twig', [
            'games' => $games
        ]);
    }

    #[Route('/telecharger-pdf', name: 'downloadPDF', methods: ['POST'])]
    public function downloadPdf(Request $request, DownloadHandler $downloadHandler): Response
    {
        $idGame = $request->request->all();
        $game = $this->entityManager->getRepository(GameFiles::class)->findOneBy(['id' => $idGame]);
        $id = $this->getUser()->getId();
        $download = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        $nbrDownload = $download->getDownload();
        $role = $download->getRoles();
        // Ajout d'une méthode : incrémenter le download dans la BDD en fonction du download (games les + téléchargés)
        $slug = $request->request->all();
        $mostDownload = $this->entityManager->getRepository(GameFiles::class)->findOneBy(['id' => $slug]);
        $mostDownload->setDownload($mostDownload->getDownload() + 1);

        if ($role[0] === "ROLE_USER") {
            if ($nbrDownload >= 0 && $nbrDownload < 151) {
                $download->setDownload($download->getDownload() + 1);
                $this->entityManager->persist($download);
                $this->entityManager->persist($mostDownload);
                $this->entityManager->flush();
                return $downloadHandler->downloadObject($game, $fileField = 'pdfFile');
            } else {
                $message = 'Vous atteint votre limite de téléchargement. Merci de nous <a href="/">contacter</a>';
                $this->addFlash('danger', $message);
                return $this->redirectToRoute('products');
            }
        } else {
            return $downloadHandler->downloadObject($game, $fileField = 'pdfFile');
        }
    }
}
