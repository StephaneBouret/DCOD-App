<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\CategoryBlog;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryBlogController extends AbstractController
{
    /* INJECTION DE DEPENDANCE*/
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/categories-blog/{slug}', name: 'category_blog')]
    public function showCategoriesBlog(Request $request, PaginatorInterface $paginatorInterface, $slug): Response
    {
        $category = $this->entityManager->getRepository(CategoryBlog::class)->findOneBy(['slug' => $slug]);
        $listCategory = $this->entityManager->getRepository(BlogPost::class)->findByCategoryPostById($category);

        if (!$category) {
            return $this->redirectToRoute('blogpost');
        }

        if (!$listCategory) {
            return $this->redirectToRoute('blogpost');
        }

        $allCategory = $paginatorInterface->paginate($listCategory, $request->query->getInt('page', 1), 6);
        return $this->render('category_blog/index.html.twig', [
            'allCategory' => $allCategory,
            'category' => $category
        ]);
    }
}
