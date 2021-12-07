<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\SearchType;
use App\Form\SearchTypeTags;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /* INJECTION DE DEPENDANCE*/
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/categories', name: 'category_filter')]
    public function index(Request $request, PaginatorInterface $paginatorInterface): Response
    {
        // With PaginatorInterface (order by RAND)
        $data = $this->entityManager->getRepository(Product::class)->findAllWithLimit();

        // On définit le nombre d'images par page
        $limit = 12;

        // On récupère le numéro de page
        $page = (int)$request->query->get("page", 1);

        // On récupère les filtres
        $filterString = $request->get("string");
        $filters = $request->get("categories");
        $filtersLevel = $request->get("levels");
        $filtersTags = $request->get("tags");
        
        // On récupère le nombre total d'images
        $total = $this->entityManager->getRepository(Product::class)->getTotalImages($filterString, $filters, $filtersLevel, $filtersTags);

        $search = new Search;
        // $form = $this->createForm(SearchType::class, $search, ['csrf_protection' => false]);
        // With PaginatorInterface
        $form = $this->createForm(SearchTypeTags::class, $search, ['csrf_protection' => false]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search, $page, $limit);
            // With PaginatorInterface
            $results = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
            $products = $paginatorInterface->paginate($results, $request->query->getInt('page', 1), $limit);
        } else {
            // $products = $this->entityManager->getRepository(Product::class)->findAll();
            // $products = $this->entityManager->getRepository(Product::class)->getPaginatedImages($page, $limit);
            // With PaginatorInterface
            $this->addFlash('danger', 'Les contenus sont soumis au droit de propriété intellectuelle et l\'utilisateur doit considérer que tous les droits sont "réservés".');
            $products = $paginatorInterface->paginate($data, $request->query->getInt('page', 1), $limit);
        }

        // With PaginatorInterface
        return $this->render('category/thematique.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
        
        // return $this->render('category/index.html.twig', [
        //     'products' => $products,
        //     'limit' => $limit,
        //     'page' => $page,
        //     'total' => $total,
        //     'form' => $form->createView()
        // ]);
    }

    #[Route('/categories/{slug}', name: 'category')]
    public function showCategory($slug): Response
    {
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['slug' => $slug]);
        $listCategory = $this->entityManager->getRepository(Product::class)->findByCategoryFieldById($category);

        if (!$category) {
            return $this->redirectToRoute('products');
        }

        if (!$listCategory) {
            return $this->redirectToRoute('products');
        }

        return $this->render('product/list.html.twig', [
            'listCategory' => $listCategory,
            'category' => $category
        ]);
    }
}
