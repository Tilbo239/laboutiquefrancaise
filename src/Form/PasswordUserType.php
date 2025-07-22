<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'constraints' => [new Length(['min' => 4, 'max' => 30])],
                'attr' => ['placeholder' => 'Entrez votre mot de passe actuel'],
                'mapped' => false, // This is used if you want to handle password validation manually
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [new Length(['min' => 4, 'max' => 30])],
                'first_options'  => [
                    'label' => 'Votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Entrez votre nouveau mot de passe'
                    ], 
                    // 'mapped' => false, // This is used if you want to handle password hashing manually
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmer votre nouveau mot de passe', 
                    'attr' => [
                        'placeholder' => 'Confirmer votre nouveau mot de passe'
                    ]
                ],
                'mapped' => false,
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'Modifier le mot de passe',
                'attr' => ['class' => 'btn btn-success'],
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];

                //Recuperer le mot de passe saisi par l'utilisateur et le comparer avec le mot de passe en base de données(dans l'entité User)
                $isValid = $passwordHasher->isPasswordValid($user, $form->get('currentPassword')->getData());
                
                if(!$isValid){
                    $form->get('currentPassword')->addError(new FormError('Votre mot de passe actuel n\'est pas conforme. Veuillez verifier la saisie.'));
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null, // This will be used to hash the password if needed
        ]);
    }
}
