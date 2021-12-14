<?php

namespace App\Controller\Admin;

use App\Entity\GameFiles;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GameFilesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GameFiles::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['name' => 'ASC'])
            ->setPaginatorPageSize(10)
            ->setPageTitle('index', 'Jeux :')
            ->setPageTitle('new', 'Créer Jeux')
            ->setEntityLabelInSingular('Jeux');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom du jeu :'),
            SlugField::new('slug', 'Slug :')->setTargetFieldName('name'),
            TextField::new('imageFile', 'Fichier image :')
                ->setFormType(VichImageType::class)
                -> setTranslationParameters([ 'form.label.delete' => 'Supprimer l\'image' ])
                ->hideOnIndex(),
            ImageField::new('illustration')
                ->setBasePath('/uploads/imagiers')
                ->onlyOnIndex(),
            TextField::new('pdfFile', 'Fichier pdf :')
                ->setFormType(VichFileType::class)
                ->hideOnIndex(),
            ImageField::new('pdf')
                ->setBasePath('/uploads/files')
                ->onlyOnDetail(),
            ChoiceField::new('category', 'Type de jeu :')->setChoices([
                    'Jeu de loto' => 'loto',
                    'Jeu de l\'oie' => 'oie',
                    'Jeu de l\'intrus' => 'intrus'
            ]),
            AssociationField::new('level', 'Niveau :'),
            IntegerField::new('download', 'Téléchargements :')
        ];
    }
}
