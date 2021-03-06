<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    // private $passwordEncoder;
    // private $crudUrlGenerator;
    private $adminUrlGenerator;

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    // public function __construct(UserPasswordHasherInterface $passwordEncoder)
    // {
    //     $this->passwordEncoder = $passwordEncoder;
    // }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Utilisateurs :')
            ->setDefaultSort(['lastname' => 'ASC'])
            ->setPaginatorPageSize(10);
    }

    public function configureActions(Actions $actions): Actions
    {
        $resendRegistrationMail  = Action::NEW('resendRegistrationMail', 'Renvoi du mail d\'enregistrement', 'fas fa-reply')->linkToCrudAction('resendRegistrationMail');
        return $actions
            ->add(Crud::PAGE_EDIT, $resendRegistrationMail)
            ->disable(Action::NEW);
        ;
    }

    public function resendRegistrationMail(AdminContext $context, Request $request)
    {
        $user = $context->getEntity()->getInstance();

        $assets = $request->getSchemeAndHttpHost();
        $urls = $this->generateUrl('register');
        $fullUrl = $assets.$urls;

        $mail = new Mail;
        $content ="Bonjour " . $user->getFirstname() . "<br/>Bienvenue sur l'application de Dis, comment on dit ?.<br><br/>Vous avez la possibilit?? d'exploiter toutes les ressources<br/>";
        $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $fullUrl . "'>valider votre compte</a>.<br/>";
        $content .= "N'oubliez pas d'utiliser la m??me adresse email pour vous enregistrer !";
        $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur l\'application de Dis, comment on dit ?', $content);

        $this->addFlash('success', 'Le mail de r??inscription destin?? ?? ' . $user->getFirstname() . ' est bien envoy??');

        $url = $this->adminUrlGenerator
        ->setController(UserCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this->redirect($url);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname', 'Pr??nom :'),
            TextField::new('lastname', 'Nom :'),
            EmailField::new('email', 'Email :'),
            ArrayField::new('roles', 'R??le :'),
            BooleanField::new('isValid', 'Valide :'),
            TextField::new('password', 'Mot de passe :')
                ->hideOnIndex(),
            IntegerField::new('download', 'Nombre de t??l??chargement'),
            DateTimeField::new('createdAt', 'Cr???? le :')->onlyOnIndex()
        ];
    }

    // public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    // {
    //     $this->encodePassword($entityInstance);
    //     parent::persistEntity($entityManager, $entityInstance);
    // }

    // public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    // {
    //     $this->encodePassword($entityInstance);
    //     parent::updateEntity($entityManager, $entityInstance);
    // }

    // private function encodePassword(User $user)
    // {
    //     if ($user->getPassword() !== null) {
    //         $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPassword()));
    //     }
    // }
}
