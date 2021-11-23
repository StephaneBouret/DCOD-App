<?php

namespace App\Controller\Admin;

use App\Entity\PictureBook;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PictureBookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PictureBook::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre :'),
            SlugField::new('slug', 'Slug :')->setTargetFieldName('title'),
            TextField::new('imageFile', 'Fichier image :')
                ->setFormType(VichImageType::class)
                -> setTranslationParameters([ 'form.label.delete' => 'Supprimer l\'image' ])
                ->hideOnIndex(),
            ImageField::new('illustration')
                ->setBasePath('/uploads/imagiers')
                ->onlyOnIndex(),
            TextField::new('coverageFile', 'Fichier couverture :')
                ->setFormType(VichImageType::class)
                ->setTranslationParameters([ 'form.label.delete' => 'Supprimer l\'image' ])
                ->hideOnIndex(),
            ImageField::new('coverage')
                ->setBasePath('/uploads/imagiers')
                ->onlyOnIndex(),
            AssociationField::new('level', 'Niveau :'),
            ChoiceField::new('tome', 'N° de tome :')->setChoices([
                'Tome 1' => 1,
                'Tome 2' => 2,
                'Tome 3' => 3
            ]),
            IntegerField::new('number_pages', 'nombre de pages :'),
            DateField::new('published_at', 'Edité le :')
                ->hideOnIndex(),
            TextField::new('subtitle', 'Titre de l\'article :')
                ->setFormTypeOptions(['required' => false])
                ->hideOnIndex(),
            TextareaField::new('description', 'Description :')
                ->setFormTypeOptions(['required' => false])
                ->hideOnIndex(),
            DateTimeField::new('updated_at')
                ->hideOnIndex(),
        ];
    }
}
