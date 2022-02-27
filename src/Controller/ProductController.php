<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /* INJECTION DE DEPENDANCE*/
    private $repo;
    private $entityManager;

    public function __construct(ProductRepository $repo, EntityManagerInterface $entityManager)
    {
        $this->repo = $repo;
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-images', name: 'products')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            // 'ps' => $this->repo->findByLevelField(1),
            // 'ms' => $this->repo->findByLevelField(2),
            // 'gs' => $this->repo->findByLevelField(3),
            // 'recent' => $this->repo->recentlyAdd(),
            // 'popular' => $this->repo->popularFilter(),
            'user' => $this->getUser()
        ]);
    }

    #[Route('/image/{slug}', name: 'product')]
    public function show($slug, Request $request): Response
    {
        $url = $request->get('url');
        if (is_null($url) || $url === "") {
            $url = "/categories";
        }
        if ($url === "/ma-liste") {
            $url = "/ma-liste";
        }

        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['slug' => $slug]);
        $catSlug = $product->getCategory()->getSlug();

        if (!$product) {
            return $this->redirectToRoute('products');
        }

        if (!$catSlug) {
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'catSlug' => $catSlug,
            'url' => $url
        ]);
    }

    // #[Route('/nouveautes', name: 'recent')]
    // public function recent(): Response
    // {
    //     $filterName = "Nouveautés";
    //     return $this->render('product/filter.html.twig', [
    //         'listFilter' => $this->repo->recentlyAdd(),
    //         'filterName' => $filterName
    //     ]);
    // }

    #[Route('/populaires', name: 'popular')]
    public function popular(): Response
    {
        $filterName = "Images les plus téléchargées";
        return $this->render('product/filter.html.twig', [
            'listFilter' => $this->repo->popularFilter(),
            'filterName' => $filterName
        ]);
    }

    #[Route('/imagier-ps', name: 'ps')]
    public function psPictureBook(): Response
    {
        $filterName = "Imagier TPS/PS";
        return $this->render('product/filter.html.twig', [
            'listFilter' => $this->repo->findByLevelField(1),
            'filterName' => $filterName
        ]);
    }

    #[Route('/imagier-ms', name: 'ms')]
    public function msPictureBook(): Response
    {
        $filterName = "Imagier MS";
        return $this->render('product/filter.html.twig', [
            'listFilter' => $this->repo->findByLevelField(2),
            'filterName' => $filterName
        ]);
    }

    #[Route('/imagier-gs', name: 'gs')]
    public function gsPictureBook(): Response
    {
        $filterName = "Imagier GS";
        return $this->render('product/filter.html.twig', [
            'listFilter' => $this->repo->findByLevelField(3),
            'filterName' => $filterName
        ]);
    }

    #[Route('/search', name: 'search', methods: ['POST'])]
    public function search(Request $request): Response
    {
        $string = $request->request->all();
        $searchResult = $this->entityManager->getRepository(Product::class)->search($string['search']);
        $filterName = "Votre recherche";
        return $this->render('product/filter.html.twig', [
            'listFilter' => $searchResult,
            'filterName' => $filterName
        ]);
    }

    // #[Route('/like', name: 'like', methods: ['POST'])]
    // public function like(Request $request): Response
    // {
    //     $userFirstname = $this->getUser()->getFirstname();
    //     $slug = $request->request->all();
    //     $image = $this->entityManager->getRepository(Product::class)->findOneBy(['slug' => $slug]);
    //     $image->setLikes($image->getLikes() + 1);
 
    //     $this->entityManager->persist($image);
    //     $this->entityManager->flush();
    //     $message = "Merci pour votre évaluation, ".$userFirstname;
    //     $message .= "\n Plus vous évaluez d'images, plus nos suggestions seront pertinentes";

    //     $this->addFlash('success', $message);
    //     // return $this->redirectToRoute('product', ['slug' => $slug['slug']]);
    //     return $this->redirectToRoute('products');
    // }

    // #[Route('/dislike', name: 'dislike', methods: ['POST'])]
    // public function dislike(Request $request): Response
    // {
    //     $userFirstname = $this->getUser()->getFirstname();
    //     $slug = $request->request->all();
    //     $image = $this->entityManager->getRepository(Product::class)->findOneBy(['slug' => $slug]);
    //     $nbrLike = $image->getLikes();

    //     if ($nbrLike > 0) {
    //         $image->setLikes($image->getLikes() - 1);
 
    //         $this->entityManager->persist($image);
    //         $this->entityManager->flush();
    //     }

    //     $message = "Merci pour votre évaluation, ".$userFirstname;
    //     $message .= "\n Plus vous évaluez d'images, plus nos suggestions seront pertinentes";

    //     $this->addFlash('danger', $message);
    //     // return $this->redirectToRoute('product', ['slug' => $slug['slug']]);
    //     return $this->redirectToRoute('products');
    // }

    #[Route('/telecharger', name: 'addDL', methods: ['POST'])]
    public function downloadImageAction(Request $request, DownloadHandler $downloadHandler): Response
    {
        $id = $request->request->all();
        $image = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
        $id = $this->getUser()->getId();
        $download = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        $nbrDownload = $download->getDownload();
        $role = $download->getRoles();
        // Ajout d'une méthode : incrémenter le likes dans la BDD en fonction du download (images les + téléchargées)
        $slug = $request->request->all();
        $mostDownload = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $slug]);
        $mostDownload->setLikes($mostDownload->getLikes() + 1);
        if ($role[0] === "ROLE_USER") {
            if ($nbrDownload >= 0 && $nbrDownload < 151) {
                $download->setDownload($download->getDownload() + 1);
                $this->entityManager->persist($download);
                $this->entityManager->persist($mostDownload);
                $this->entityManager->flush();
                return $downloadHandler->downloadObject($image, $fileField = 'imageFile');
            } else {
                $message = 'Vous atteint votre limite de téléchargement. Merci de nous <a href="/">contacter</a>';
                $this->addFlash('danger', $message);
                return $this->redirectToRoute('products');
            }
        } else {
            return $downloadHandler->downloadObject($image, $fileField = 'imageFile');
        }
    }
}
