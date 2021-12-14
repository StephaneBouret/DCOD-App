<?php

namespace App\Controller\Admin;

use App\Entity\IndexWords;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IndexWordsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return IndexWords::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['title' => 'ASC'])
            ->setPaginatorPageSize(10)
            ->setPageTitle('index', 'Index :')
            ->setPageTitle('new', 'Créer Index')
            ->setEntityLabelInSingular('Index');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Nom :'),
            TextField::new('page', 'N° de page :'),
            ChoiceField::new('tome', 'N° de tome :')->setChoices([
                'Tome 1' => 1,
                'Tome 2' => 2,
                'Tome 3' => 3
            ]),
            ChoiceField::new('level', 'Niveau :')->setChoices([
                'TPS/PS' => 'TPS/PS',
                'MS' => 'MS',
                'GS' => 'GS'
            ]),
            AssociationField::new('alphabet', 'Lettre :')
        ];
    }
}
