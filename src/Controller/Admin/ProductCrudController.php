<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['name' => 'ASC'])
            ->setPaginatorPageSize(10);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom :'),
            SlugField::new('slug', 'Slug :')->setTargetFieldName('name'),
            // ImageField::new('illustration')
            //     ->setBasePath('uploads/')
            //     ->setUploadDir('public/uploads')
            //     ->setUploadedFileNamePattern('[randomhash].[extension]')
            //     ->setSortable(false),
            TextField::new('imageFile', 'Fichier image :')
                ->setFormType(VichImageType::class)
                -> setTranslationParameters([ 'form.label.delete' => 'Supprimer l\'image' ])
                ->hideOnIndex(),
            ImageField::new('illustration')
                ->setBasePath('/uploads/imagiers')
                ->onlyOnIndex(),
            TextareaField::new('description', 'Description :')
                ->setFormTypeOptions(['required' => false])
                ->hideOnIndex(),
            TextField::new('subtitle', 'Sous-titre :')
                ->setFormTypeOptions(['required' => false]),
            ChoiceField::new('tome', 'N° de tome :')->setChoices([
                'Tome 1' => 1,
                'Tome 2' => 2,
                'Tome 3' => 3
            ]),
            IntegerField::new('page', 'N° de page :')
                ->formatValue(function ($value) {
                    return $value < 0 ? 0 : $value;
                }),
            AssociationField::new('category', 'Catégorie :'),
            AssociationField::new('level', 'Niveau :'),
            IntegerField::new('likes', 'Likes :')
        ];
    }
}
