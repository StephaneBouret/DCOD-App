<?php

namespace App\Controller\Admin;

use App\Entity\Plan;
use App\Entity\User;
use App\Entity\Level;
use App\Entity\Order;
use App\Entity\Header;
use App\Entity\Images;
use App\Entity\School;
use App\Entity\Contact;
use App\Entity\Product;
use App\Entity\Alphabet;
use App\Entity\BlogPost;
use App\Entity\Category;
use App\Entity\Comments;
use App\Entity\GameFiles;
use App\Entity\IndexWords;
use App\Entity\PictureBook;
use App\Entity\CategoryBlog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // return parent::index();
        $countUser = $this->entityManager->getRepository(User::class)->countUsersConnected();
        $listUserActivity = $this->entityManager->getRepository(User::class)->listUsersLastActivity();
        return $this->render('admin/dashboard.html.twig', [
            'countUser' => $countUser,
            'listUserActivity' => $listUserActivity
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dis, comment on dit ?');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Niveaux', 'fas fa-school', Level::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Produits', 'fad fa-image', Product::class);
        yield MenuItem::linkToCrud('Headers', 'fad fa-desktop', Header::class);
        yield MenuItem::linkToCrud('Ecoles', 'fas fa-school', School::class);
        yield MenuItem::linkToCrud('Imagiers', 'fas fa-book-open', PictureBook::class);
        yield MenuItem::linkToCrud('Images', 'fas fa-camera', Images::class);
        yield MenuItem::linkToCrud('Abonnements', 'fab fa-cc-stripe', Plan::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Alphabet', 'fas fa-font', Alphabet::class);
        yield MenuItem::linkToCrud('Index', 'fas fa-indent', IndexWords::class);
        yield MenuItem::linkToCrud('Jeux', 'fas fa-dice', GameFiles::class);
        yield MenuItem::linkToCrud('Blog', 'fas fa-comments', BlogPost::class);
        yield MenuItem::linkToCrud('Catégories du post', 'fas fa-folder-open', CategoryBlog::class);
        yield MenuItem::linkToCrud('Commentaires', 'far fa-comments', Comments::class);
        yield MenuItem::linkToCrud('Emails', 'fas fa-envelope', Contact::class);
        yield MenuItem::linkToRoute('Accueil', 'fas fa-laptop-house', 'products');
        yield MenuItem::linkToLogout('Se déconnecter', 'fas fa-sign-out-alt');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
