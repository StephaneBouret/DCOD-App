<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ImagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Images::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('pictureFile', 'Fichier image :')
                ->setFormType(VichImageType::class)
                -> setTranslationParameters([ 'form.label.delete' => 'Supprimer l\'image' ])
                ->hideOnIndex(),
            ImageField::new('illustration')
                ->setBasePath('/uploads/imagiers')
                ->onlyOnIndex(),
            AssociationField::new('pictureBook', 'Imagier :'),
        ];
    }

}
