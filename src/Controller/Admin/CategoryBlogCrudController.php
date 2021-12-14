<?php

namespace App\Controller\Admin;

use App\Entity\CategoryBlog;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryBlogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CategoryBlog::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Titre de la catégorie :'),
            SlugField::new('slug', 'Slug :')->setTargetFieldName('name'),
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
            ->setPageTitle('index', 'Catégories du post :')
            ->setPageTitle('new', 'Créer une catégorie')
            ->setEntityLabelInSingular('Catégories du post')
        ;
    }
}
