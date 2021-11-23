<?php

namespace App\Form;

use App\Entity\Level;
use App\Classe\Search;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchTypeTags extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('string', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Tapez une ou plusieurs lettres',
                    'class' => 'form-control-sm',
                    'autocomplete' => 'off'
                ]
            ])
            ->add('levels', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Level::class,
                'query_builder' => function (EntityRepository $ey) {
                    return $ey->createQueryBuilder('l');
                },
                'multiple' => true,
                'expanded' => true
            ])
            ->add('tags', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'multiple' => true,
                'expanded' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'select-tags'
                ]
            ])
            ->add('product', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Product::class,
                'multiple' => true,
                'expanded' => false,
                'query_builder' => function (EntityRepository $pr) {
                    return $pr->createQueryBuilder('p')
                    ->orderBy('p.name', 'ASC');
                },
                // Ajout du niveau en plus du nom dans le select2
                'choice_label' => function ($allChoices, $currentChoiceKey)
                {
                    return $allChoices->getName() . " - (" . $allChoices->getLevel() . ")";
                },
                // 'choice_label' => 'name',
                'attr' => [
                    'class' => 'select-product'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Chercher',
                'attr' => [
                    'class' => 'btn-block btn-red w-100'
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'crsf_protection' => false,
            "allow_extra_fields" => true
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
