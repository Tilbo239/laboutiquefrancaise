<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [new Length(['min' => 2, 'max' => 30])],
                'attr' => ['placeholder' => 'Entrez votre prénom'],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [new Length(['min' => 4, 'max' => 30])],
                'attr' => ['placeholder' => 'Entrez votre nom'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'attr' => ['placeholder' => 'Entrez votre adresse e-mail'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [new Length(['min' => 4, 'max' => 30])],
                'first_options'  => [
                    'label' => 'Votre mot de passe', 
                    'attr' => [
                        'placeholder' => 'Entrez votre adresse e-mail'
                    ], 
                    // 'mapped' => false, // This is used if you want to handle password hashing manually
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmer votre mot de passe', 
                    'attr' => [
                        'placeholder' => 'Entrez votre adresse e-mail'
                    ]
                ],
                'mapped' => false,
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr' => ['class' => 'btn btn-success'],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity(
                    fields: ['email'],
                    message: 'Un compte avec cette adresse e-mail existe déjà.'
                ),
            ],
            'data_class' => User::class,
        ]);
    }
}
