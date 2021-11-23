<?php

namespace App\Controller;

use DateTime;
use App\Classe\Mail;
use App\Entity\User;
use App\Classe\Search;
use App\Entity\Blogpost;
use App\Entity\Comments;
use App\Form\BlogpostType;
use App\Form\CommentsType;
use App\Entity\CategoryBlog;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SearchBlogType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogpostController extends AbstractController
{
    /* INJECTION DE DEPENDANCE*/
    private $entityManager;

    /* Injection du Slug via String Component */
    private $slugger;
    
    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }

    #[Route('/blog', name: 'blogpost')]
    public function index(Request $request, PaginatorInterface $paginatorInterface): Response
    {
        // Fetch des posts validés :
        $data = $this->entityManager->getRepository(Blogpost::class)->findPostsValidated();
        // Calcul du nombre de post par catégorie :
        $catPost = $this->entityManager->getRepository(CategoryBlog::class)->countPostByCategory();
        // Fetch des 5 derniers posts enregistrés :
        $lastPost = $this->entityManager->getRepository(Blogpost::class)->lastPost();
        // Récupération de l'utilisateur actif
        $user = $this->getUser();

        $search = new Search;

        $form = $this->createForm(SearchBlogType::class, $search, ['csrf_protection' => false]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search, $page, $limit);
            // With PaginatorInterface
            $results = $this->entityManager->getRepository(BlogPost::class)->findWithSearch($search);
            $blogposts = $paginatorInterface->paginate($results, $request->query->getInt('page', 1), 6);
        } else {
            // $products = $this->entityManager->getRepository(Product::class)->findAll();
            // $products = $this->entityManager->getRepository(Product::class)->getPaginatedImages($page, $limit);
            // With PaginatorInterface
            $blogposts = $paginatorInterface->paginate($data, $request->query->getInt('page', 1), 6);
        }

        // $blogposts = $paginatorInterface->paginate($data, $request->query->getInt('page', 1), 6);
        return $this->render('blogpost/index.html.twig', [
            'blogposts' => $blogposts,
            'catPost' => $catPost,
            'lastPost' => $lastPost,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route('/blog/{slug}', name: 'single_post')]
    public function details($slug, Request $request): Response
    {
        $singleBlog = $this->entityManager->getRepository(Blogpost::class)->findOneBy(['slug' => $slug]);
        if (!$singleBlog) {
            $this->addFlash('danger', 'Ce sujet n\'existe pas !');
            return $this->redirectToRoute('blogpost');
        }
        
        $idSingleBlog = $singleBlog->getId();
        $countComment = $this->entityManager->getRepository(Comments::class)->countCommentsByPost($idSingleBlog);

        // Partie commentaires
        // On crée le commentaire "vierge"
        $comment = new Comments;

        // On génère le formulaire
        $commentForm = $this->createForm(CommentsType::class, $comment);
        $commentForm->handleRequest($request);

        // Traitement du formulaire
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $userEmail = $this->getUser()->getEmail();
            $comment->setCreatedAt(new DateTime());
            $comment->setAnnonces($singleBlog);
            $comment->setEmail($userEmail);

            // On récupère le contenu du champ parentid
            $parentid = $commentForm->get("parentid")->getData();

            // On va chercher le commentaire correspondant
            if ($parentid != null) {
                $parent = $this->entityManager->getRepository(Comments::class)->find($parentid);
            }

            // On définit le parent
            $comment->setParent($parent ?? null);

            // Envoi en BDD
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            // $this->addFlash('success', 'Votre commentaire a bien été envoyé');
            return $this->redirectToRoute('single_post', ['slug' => $singleBlog->getSlug()]);
        }

        return $this->render('blogpost/details.html.twig', [
            'singleBlog' => $singleBlog,
            'countComment' => $countComment,
            'commentForm' => $commentForm->createView()
        ]);
    }

    #[Route('/nouveau-sujet', name: 'add_new_post')]
    public function newPost(Request $request): Response
    {
        $newPost = new Blogpost;

        $form = $this->createForm(BlogpostType::class);
        $form->handleRequest($request);
        $userId = $this->getUser()->getId();
        $idUser = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            // On transforme le titre en slug
            $slugData = $form->get('title')->getData();
            $slug = strtolower($this->slugger->slug($slugData));

            // Traitement du formulaire
            $titleData = ucwords($form->get('title')->getData());
            $contentData = $form->get('content')->getData();
            $cat = $form->get('categoryPost')->getData();
            $imageFile = $form->get('imageFile')->getData();
            $newPost->setTitle($titleData);
            $newPost->setContent($contentData);
            $newPost->setSlug($slug);
            $newPost->setUsers($idUser);
            $newPost->setCreatedAt(new \DateTime());
            $newPost->setCategoryPost($cat);
            $newPost->setImageFile($imageFile);

            // Envoi en BDD
            $this->entityManager->persist($newPost);
            $this->entityManager->flush();

            $mail = new Mail();
            $content = "Bonjour,<br/> Vous venez de recevoir une demande de validation pour un post<br/>Merci de vous connecter.";
            $mail->send('contact@discommentondit.fr', 'Administrateur', 'Demande de validation', $content);

            $this->addFlash('success', 'Votre sujet a bien été envoyé. Il sera publié après validation.');
            return $this->redirectToRoute('blogpost');
        }

        return $this->render('blogpost/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/editer-blog/{slug}', name: 'edit_post')]
    public function edit($slug, Request $request): Response
    {
        $singleBlog = $this->entityManager->getRepository(Blogpost::class)->findOneBy(['slug' => $slug]);
        if (!$singleBlog) {
            $this->addFlash('danger', 'Ce sujet n\'existe pas !');
            return $this->redirectToRoute('blogpost');
        }
        
        $editBlogForm = $this->createForm(BlogpostType::class, $singleBlog);

        // On génère le formulaire
        $editBlogForm->handleRequest($request);

        // Traitement du formulaire
        if ($editBlogForm->isSubmitted() && $editBlogForm->isValid()) {
            // Envoi en BDD
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre sujet a bien été modifié');
            return $this->redirectToRoute('blogpost');
        }

        return $this->render('blogpost/edit.html.twig', [
            'editBlogForm' => $editBlogForm->createView()
        ]);
    }

    #[Route('/charte-des-commentaires', name: 'comments_policy')]
    public function policy(): Response
    {
        return $this->render('blogpost/policy.html.twig');
    }

    #[Route('/notifier-commentaire', name: 'notify_comment', methods: ['POST'])]
    public function notify(Request $request): Response
    {
        $data = $request->request->all();
        $comment = $this->entityManager->getRepository(Comments::class)->findOneBy(['id' => $data]);
        $email = $comment->getEmail();
        $nickname = $comment->getNickname();
        $content = $comment->getContent();
        $id = $comment->getId();
        $content = "Bonjour,<br/> Vous venez de recevoir une notification de demande de modération d\'un commentaire.<br/><br/>";
        $content .= "Email de l'auteur : " . $email . "<br/>";
        $content .= "Pseudo de l'auteur : " . $nickname . "<br/>";
        $content .= "Contenu du commentaire : " . $content . "<br/>";
        $content .= "Numéro du commentaire : " . $id;
        $mail = new Mail();
        $mail->send('contact@discommentondit.fr', 'Administrateur', 'Demande de modération sur l\'application Dis, comment on dit', $content);
        $this->addFlash('success', 'Votre demande de modération a bien été envoyé. Nous la traiterons dans les plus brefs délais.');
        return $this->redirectToRoute('blogpost');
    }
}
