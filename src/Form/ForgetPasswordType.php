<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ForgetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('new_password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
            'label' => 'Votre nouveau mot de passe',
            'required' => true,
            'first_options' => [
                'label' => 'Votre nouveau mot de passe',
                'label_attr' => [
                    'title' => 'Pour des raisons de sécurité, votre mot de passe doit contenir au minimum 12 caractères dont 1 lettre majuscule, 1 lettre minuscule, 1 chiffre et 1 caractère spécial.'
                ],
                'attr' => [
                    'placeholder' => 'Merci de saisir votre mot de passe',
                    'pattern' => '^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{12,}$',
                    'title' => 'Pour des raisons de sécurité, votre mot de passe doit contenir au minimum 12 caractères dont 1 lettre majuscule, 1 lettre minuscule, 1 chiffre et 1 caractère spécial.',
                    'maxlength' => 255
                ]
            ],
            'second_options' => [
                'label' => 'Confirmez votre mot de passe',
                'attr' => [
                    'placeholder' => 'Merci de confirmer votre mot de passe',
                    'pattern' => '^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{12,}$',
                    'title' => 'Pour des raisons de sécurité, votre mot de passe doit contenir au minimum 12 caractères dont 1 lettre majuscule, 1 lettre minuscule, 1 chiffre et 1 caractère spécial.',
                    'maxlength' => 255
                ]
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label' => "Mettre à jour mon mot de passe",
            'attr' => [
                'class' => 'login__sign-in'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
