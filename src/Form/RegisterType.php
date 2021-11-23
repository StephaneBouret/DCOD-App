<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('firstname', TextType::class, [
            'label' => 'Votre prénom :',
            'constraints' => new Length([
                'min' => 2,
                'max' => 30,
                'minMessage' => 'Votre prénom doit comporter au moins {{ limit }} caractères',
                'maxMessage' => 'Votre prénom ne peut excéder {{ limit }} caractères',
            ]),
            'attr' => [
                'placeholder' => 'Merci de saisir votre prénom'
            ]
        ])
        ->add('lastname', TextType::class, [
            'label' => 'Votre nom :',
            'attr' => [
                'placeholder' => 'Merci de saisir votre nom'
            ]
        ])
        ->add('email', EmailType::class, [
            'label' => 'Votre email :',
            'required' => true,
            'attr' => [
                'placeholder' => 'Merci de saisir votre adresse email'
            ]
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
            'label' => 'Votre mot de passe',
            'required' => true,
            'first_options' => [
                'label' => 'Mot de passe',
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
            'label' => "S'inscrire",
            'attr' => [
                'class' => 'login__sign-in'
            ]
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
