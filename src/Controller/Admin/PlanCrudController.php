<?php

namespace App\Controller\Admin;

use App\Entity\Plan;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class PlanCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Plan::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom :'),
            SlugField::new('slug', 'Slug :')->setTargetFieldName('name'),
            BooleanField::new('isBest', 'Mise en avant :'),
            IntegerField::new('duration', 'Durée :'),
            MoneyField::new('price', 'Prix annuel :')->setCurrency('EUR'),
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
            ->setPageTitle('index', 'Abonnements :')
            ->setPageTitle('new', 'Créer un abonnement')
        ;
    }
}
