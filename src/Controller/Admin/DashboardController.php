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
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private $entityManager;
    private $adminUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // return parent::index();
        $countUser = $this->entityManager->getRepository(User::class)->countUsersConnected();
        $listUserActivity = $this->entityManager->getRepository(User::class)->listUsersLastActivity();
        $listAllUserByDl = $this->entityManager->getRepository(User::class)->findAllUserByDownload();
        $popularImg = $this->entityManager->getRepository(Product::class)->popularFilter();
        $popularGames = $this->entityManager->getRepository(GameFiles::class)->popularFilter();
        return $this->render('admin/dashboard.html.twig', [
            'countUser' => $countUser,
            'listUserActivity' => $listUserActivity,
            'listAllUserByDl' => $listAllUserByDl,
            'popularImg' => $popularImg,
            'popularGames' => $popularGames
        ]);
    }

    /**
     * @Route("/admin/remise-zero-download/{id}", name="download_restock")
     * @IsGranted("ROLE_ADMIN")
     */
    public function restock($id): Response
    {
        $resetDownload = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        $resetDownload->setDownload(0);
        $this->entityManager->persist($resetDownload);
        $this->entityManager->flush();

        $this->addFlash('success', 'La remise ?? z??ro des t??l??chargements de ' . $resetDownload->getFirstname() . ' ' . $resetDownload->getLastname() . ' est bien effectu??e');
        
        return $this->redirectToRoute('admin');
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
        yield MenuItem::linkToCrud('Cat??gories', 'fas fa-list', Category::class);
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
        yield MenuItem::linkToCrud('Cat??gories du post', 'fas fa-folder-open', CategoryBlog::class);
        yield MenuItem::linkToCrud('Commentaires', 'far fa-comments', Comments::class);
        yield MenuItem::linkToCrud('Emails', 'fas fa-envelope', Contact::class);
        yield MenuItem::linkToRoute('Accueil', 'fas fa-laptop-house', 'products');
        yield MenuItem::linkToLogout('Se d??connecter', 'fas fa-sign-out-alt');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
