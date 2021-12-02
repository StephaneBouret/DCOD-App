<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Créé le :'),
            TextField::new('user.getFullName', 'Utilisateur'),
            MoneyField::new('total', 'Total produit')->setCurrency('EUR'),
            BooleanField::new('isPaid', 'Payé :'),
            ArrayField::new('orderDetails', 'Produits achetés :'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        // the visible title at the top of the page and the content of the <title> element
        // it can include these placeholders:
        //   %entity_name%, %entity_as_string%,
        //   %entity_id%, %entity_short_id%
        //   %entity_label_singular%, %entity_label_plural%
        // ajout dernière ligne pour activer ckeditor + ligne 30 ->setFormType(CKEditorType::class)
        ->setPageTitle('index', 'Commandes :')
        ->setPageTitle('detail', 'Commande :')
        ->setPageTitle('edit', 'Modifier la commande :')
        ->setPageTitle('new', 'Créer une commande')
        ->setDefaultSort(['id' => 'DESC'])
    ;
    }
}
