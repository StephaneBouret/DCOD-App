<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    // private $passwordEncoder;
    // private $crudUrlGenerator;

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // public function __construct(UserPasswordHasherInterface $passwordEncoder, CrudUrlGenerator $crudUrlGenerator)
    // {
    //     $this->passwordEncoder = $passwordEncoder;
    //     $this->crudUrlGenerator = $crudUrlGenerator;
    // }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['lastname' => 'ASC'])
            ->setPaginatorPageSize(10);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname'),
            TextField::new('lastname'),
            EmailField::new('email'),
            ArrayField::new('roles'),
            BooleanField::new('isValid'),
            TextField::new('password')
                ->hideOnIndex(),
            IntegerField::new('download', 'Nombre de téléchargement')
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
