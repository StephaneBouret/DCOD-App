<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangeEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('new_email', RepeatedType::class, [
            'type' => EmailType::class,
            'mapped' => false,
            'invalid_message' => 'L\'email et la confirmation doivent être identiques',
            'required' => true,
            'first_options' => [
                'label'=> false,
                'attr' => [
                    'placeholder' => 'Mon nouvel email',
                    'class' => 'form-control form-register effect-17 has-content newmail',
                    'autocomplete' => 'off'
                ]
            ],
            'second_options' => [
                'label'=> false,
                'attr' => [
                    'placeholder' => 'Confirmation du nouvel email',
                    'class' => 'form-control form-register effect-17 has-content newmail',
                    'autocomplete' => 'off'
                ]
            ],
            'constraints' => new Email(),
        ])
        ->add('password_for_email', PasswordType::class, [
            'label' => 'Mon mot de passe actuel',
            'required' => true,
            'mapped' => false,
            'attr' => [
                'placeholder' => 'Veuillez saisir votre mot de passe actuel',
                'class' => 'form-control form-register effect-17 has-content newmail'
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label' => "Modifier mon email",
            'attr' => [
                'class' => 'login__sign-in'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
