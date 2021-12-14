<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BlogPostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BlogPost::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre du sujet :'),
            SlugField::new('slug', 'Slug :')->setTargetFieldName('title'),
            TextareaField::new('content', 'Contenu du post :')->setFormType(CKEditorType::class),
            AssociationField::new('categoryPost', 'Catégories :'),
            TextField::new('imageFile', 'Fichier image :')
                ->setFormType(VichImageType::class)
                ->setTranslationParameters([ 'form.label.delete' => 'Supprimer l\'image' ])
                ->hideOnIndex(),
            ImageField::new('illustration')
                ->setBasePath('/uploads/blog')
                ->onlyOnIndex(),
            DateTimeField::new('createdAt', 'Publié le :'),
            BooleanField::new('isActive', 'Actif :'),
            AssociationField::new('users', 'Auteur :'),
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
            ->setPageTitle('index', 'Sujets :')
            ->setPageTitle('new', 'Créer un post')
            ->setEntityLabelInSingular('Sujets')
            ->setDefaultSort(['id' => 'DESC'])
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
        ;
    }
}
