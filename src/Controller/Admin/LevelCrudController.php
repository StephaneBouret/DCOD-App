<?php

namespace App\Controller\Admin;

use App\Entity\Level;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LevelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Level::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom :')
                ->addCssClass('text-capitalize'),
            SlugField::new('slug')
                ->setTargetFieldName('name')
                ->hideOnIndex(),
        ];
    }
}
