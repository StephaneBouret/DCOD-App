<?php

namespace App\Controller\Admin;

use App\Entity\School;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SchoolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return School::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre :'),
            TextField::new('imageFile', 'Fichier image :')
                ->setFormType(VichImageType::class)
                -> setTranslationParameters([ 'form.label.delete' => 'Supprimer l\'image' ])
                ->hideOnIndex(),
            ImageField::new('illustration')
                ->setBasePath('/uploads/imagiers')
                ->onlyOnIndex(),
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
            ->setPageTitle('index', 'Ecoles :')
            ->setPageTitle('edit', 'Modifier l\'école :')
            ->setPageTitle('new', 'Créer une école :')
        ;
    }
}
