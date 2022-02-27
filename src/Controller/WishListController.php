<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WishListController extends AbstractController
{
    /* INJECTION DE DEPENDANCE*/
    private $repo;
    private $entityManager;
    
    public function __construct(ProductRepository $repo, EntityManagerInterface $entityManager)
    {
        $this->repo = $repo;
        $this->entityManager = $entityManager;
    }

    #[Route('/ma-liste', name: 'mylist')]
    public function index(Request $request): Response
    {
        $url = $this->generateUrl('mylist');
        if ($this->getUser()) {
            return $this->render('wish_list/index.html.twig', [
                'user' => $this->getUser(),
                'url' => $url
            ]);
        }
    }

    #[Route('/ajouter-a-ma-liste', name: 'addList', methods: ['POST'])]
    public function addList(Request $request): Response
    {
        $userFirstname = $this->getUser()->getFirstname();
        $id = $request->request->all();
        $image = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
        $image->setWishlist($image->getWishlist() + 1);

        $user = $this->getUser();
        $user->addWishlist($image);
 
        $this->entityManager->persist($image);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $message = "Merci pour votre ajout, ".$userFirstname;
        $message .= "<br>Vous pouvez retrouvez votre image en cliquant sur l'onglet <a href='/ma-liste'>Ma liste</a>";

        $this->addFlash('success', $message);
        return $this->redirectToRoute('category_filter');
    }

    #[Route('/supprimer-de-ma-liste', name: 'removeList', methods: ['POST'])]
    public function removeList(Request $request): Response
    {
        $userFirstname = $this->getUser()->getFirstname();
        $id = $request->request->all();
        $image = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
        $nbrWish = $image->getWishlist();

        if ($nbrWish > 0) {
            $image->setWishlist($image->getWishlist() - 1);
            $user = $this->getUser();
            $user->removeWishlist($image);
 
            $this->entityManager->persist($image);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        $message = "Vous avez correctement enlevé cette image de votre liste, ".$userFirstname;

        $this->addFlash('warning', $message);
        return $this->redirectToRoute('mylist', ['user' => $this->getUser()]);
    }
}
