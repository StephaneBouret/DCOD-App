<?php

namespace App\Controller\Admin;

use App\Entity\Alphabet;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AlphabetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Alphabet::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('letter', 'Lettre :'),
            SlugField::new('slug', 'Slug :')->setTargetFieldName('letter'),
        ];
    }
}
