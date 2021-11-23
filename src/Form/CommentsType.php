<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre Pseudo*',
                    'class' => 'form-control',
                    'autocomplete' => 'off'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre Commentaire*',
                    'class' => 'form-control',
                ]
            ])
            ->add('rgpd', CheckboxType::class, [
                'label'    => 'En cochant cette case, je reconnais avoir pris connaissance de la charte RGPD de Dis, comment on dit ? et en accepte les termes.',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('parentid', HiddenType::class, [
                'mapped' => false
            ])
            ->add('commenter', SubmitType::class, [
                'label' => "Commenter",
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
