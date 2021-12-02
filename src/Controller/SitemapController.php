<?php

namespace App\Controller;

use App\Repository\AlphabetRepository;
use App\Repository\BlogPostRepository;
use App\Repository\CategoryBlogRepository;
use App\Repository\CategoryRepository;
use App\Repository\PictureBookRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'sitemap', defaults: ['_format' => 'xml'])]
    public function index(
        Request $request,
        ProductRepository $productRepository,
        AlphabetRepository $alphabetRepository,
        PictureBookRepository $pictureBookRepository,
        CategoryRepository $categoryRepository,
        CategoryBlogRepository $categoryBlogRepository,
        BlogPostRepository $blogPostRepository
        ): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];
        
        $urls[] = ['loc' => $this->generateUrl('home')];
        $urls[] = ['loc' => $this->generateUrl('about')];
        $urls[] = ['loc' => $this->generateUrl('account')];
        $urls[] = ['loc' => $this->generateUrl('account_edit')];
        $urls[] = ['loc' => $this->generateUrl('account_password')];
        $urls[] = ['loc' => $this->generateUrl('admin')];
        $urls[] = ['loc' => $this->generateUrl('application')];
        $urls[] = ['loc' => $this->generateUrl('blogpost')];
        $urls[] = ['loc' => $this->generateUrl('add_new_post')];
        $urls[] = ['loc' => $this->generateUrl('comments_policy')];
        $urls[] = ['loc' => $this->generateUrl('category_filter')];
        $urls[] = ['loc' => $this->generateUrl('contact')];
        $urls[] = ['loc' => $this->generateUrl('create_user')];
        $urls[] = ['loc' => $this->generateUrl('forget_password')];
        $urls[] = ['loc' => $this->generateUrl('game_files')];
        $urls[] = ['loc' => $this->generateUrl('glossary')];
        $urls[] = ['loc' => $this->generateUrl('legal-notices')];
        $urls[] = ['loc' => $this->generateUrl('prevacy-policy')];
        $urls[] = ['loc' => $this->generateUrl('picture_book')];
        $urls[] = ['loc' => $this->generateUrl('products')];
        $urls[] = ['loc' => $this->generateUrl('popular')];
        $urls[] = ['loc' => $this->generateUrl('ps')];
        $urls[] = ['loc' => $this->generateUrl('ms')];
        $urls[] = ['loc' => $this->generateUrl('gs')];
        $urls[] = ['loc' => $this->generateUrl('register')];
        $urls[] = ['loc' => $this->generateUrl('support_categories')];
        $urls[] = ['loc' => $this->generateUrl('register_help')];
        $urls[] = ['loc' => $this->generateUrl('resetMyPw')];
        $urls[] = ['loc' => $this->generateUrl('whyNoReset')];
        $urls[] = ['loc' => $this->generateUrl('myAccount')];
        $urls[] = ['loc' => $this->generateUrl('disconnect')];
        $urls[] = ['loc' => $this->generateUrl('changeParameters')];
        $urls[] = ['loc' => $this->generateUrl('changeEmail')];
        $urls[] = ['loc' => $this->generateUrl('changeMyPw')];
        $urls[] = ['loc' => $this->generateUrl('accessTopics')];
        $urls[] = ['loc' => $this->generateUrl('support')];
        $urls[] = ['loc' => $this->generateUrl('support_us')];
        $urls[] = ['loc' => $this->generateUrl('become_partner')];
        $urls[] = ['loc' => $this->generateUrl('mylist')];
        $urls[] = ['loc' => $this->generateUrl('pricing')];

        foreach ($blogPostRepository->findAll() as $blogPost) {
            $urls[] = [
                'loc' => $this->generateUrl('single_post', ['slug' => $blogPost->getSlug()]),
                'lastmod' => $blogPost->getCreatedAt()->format('Y-m-d')
            ];
        }

        foreach ($categoryBlogRepository->findAll() as $categoryBlog) {
            $urls[] = [
                'loc' => $this->generateUrl('category_blog', ['slug' => $categoryBlog->getSlug()])
            ];
        }

        foreach ($categoryRepository->findAll() as $category) {
            $urls[] = [
                'loc' => $this->generateUrl('category', ['slug' => $category->getSlug()])
            ];
        }

        foreach ($alphabetRepository->findAll() as $alphabet) {
            $urls[] = [
                'loc' => $this->generateUrl('single_glossary', ['slug' => $alphabet->getSlug()])
            ];
        }

        foreach ($pictureBookRepository->findAll() as $pictureBook) {
            $urls[] = [
                'loc' => $this->generateUrl('single_picture_book', ['slug' => $pictureBook->getSlug()]),
                'lastmod' => $pictureBook->getPublishedAt()->format('Y-m-d')
            ];
        }

        foreach ($productRepository->findAll() as $product) {
            $urls[] = [
                'loc' => $this->generateUrl('product', ['slug' => $product->getSlug()]),
                'lastmod' => $product->getUpdatedAt()->format('Y-m-d')
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $hostname
            ]),
            200
            );

        $response->headers->set('Content-type', 'text/xml');

        return $response;
    }
}
