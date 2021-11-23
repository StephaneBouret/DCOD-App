<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('old_password', PasswordType::class, [
            'label' => 'Mon mot de passe actuel',
            'mapped' => false,
            'required' => true,
            'attr' => [
                'placeholder' => 'Veuillez saisir votre mot de passe actuel'
            ]
        ])
        ->add('new_password', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false,
            'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
            'label' => 'Mon nouveau mot de passe',
            'required' => true,
            'first_options' => [
                'label' => 'Mon nouveau mot de passe',
                'label_attr' => [
                    'title' => 'Pour des raisons de sécurité, votre mot de passe doit contenir au minimum 12 caractères dont 1 lettre majuscule, 1 lettre minuscule, 1 chiffre et 1 caractère spécial.'
                ],
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nouveau mot de passe',
                    'pattern' => '^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{12,}$',
                    'title' => 'Pour des raisons de sécurité, votre mot de passe doit contenir au minimum 12 caractères dont 1 lettre majuscule, 1 lettre minuscule, 1 chiffre et 1 caractère spécial.',
                    'maxlength' => 255
                ]
            ],
            'second_options' => [
                'label' => 'Confirmez votre nouveau mot de passe',
                'attr' => [
                    'placeholder' => 'Merci de confirmer votre nouveau mot de passe',
                    'pattern' => '^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{12,}$',
                    'title' => 'Pour des raisons de sécurité, votre mot de passe doit contenir au minimum 12 caractères dont 1 lettre majuscule, 1 lettre minuscule, 1 chiffre et 1 caractère spécial.',
                    'maxlength' => 255
                ]
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label' => "Mettre à jour",
            'attr' => [
                'class' => 'login__sign-in'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
