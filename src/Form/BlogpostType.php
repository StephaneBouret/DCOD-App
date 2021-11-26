<?php

namespace App\Form;

use App\Entity\Blogpost;
use App\Entity\CategoryBlog;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BlogpostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Titre du sujet*',
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ]
            ])
            // ->add('slug')
            ->add('content', CKEditorType::class, [
                'config_name' => 'main_config',
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre contenu*',
                    'class' => 'form-control',
                ],
                'constraints'=>[
                    new NotBlank(),
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label' => 'Image :',
                'delete_label' => 'Supprimer l\'image',
            ])
            ->add('categoryPost', EntityType::class, [
                'label' => 'Catégorie du post :*',
                'required' => true,
                'class' => CategoryBlog::class,
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                'empty_data' => null,
                'attr' => [
                    'class' => 'post-category'
                ],
                'constraints'=>[
                    new NotBlank(),
                ]
            ])
            // ->add('createdAt')
            // ->add('isActive')
            // ->add('users')
            ->add('submit', SubmitType::class, [
                'label' => "Créer",
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blogpost::class,
        ]);
    }
}
