<?php

namespace App\Form;

use App\Entity\User;
use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UpdateUserType extends AbstractType
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
                'disabled' => true,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Merci de saisir votre adresse email'
                ]
            ])
            ->add('phone_number', PhoneNumberType::class, [
                'default_region' => 'FR',
                'format' => PhoneNumberFormat::NATIONAL,
                'label' => 'Votre téléphone :',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Merci de saisir votre téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Modifier mon profil",
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
