<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => new Length(min: 2, max: 30 ),
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre nom',
                ]
            ])

            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => new Length(min: 2, max: 30 ),
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre prénom',
                ]
            ])
            
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => new Length(min: 2, max: 100 ),
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre email',
                ]
            ])

            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr' => [
                    'placeholder' => 'Entrez votre ville'
                ]
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et sa confirmation doivent être identique',
                'label' => 'Mot de passe',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe', 
                                    'attr' => ['placeholder' => 'Veuillez saisir votre mot de passe'
                                    ]],
                'second_options' => ['label' => 'Confirmation mot de passe', 
                                    'attr' => ['placeholder' => 'Veuillez confirmer votre mot de passe'
                                    ]],
            ])

            ->add('acceptCGV', CheckboxType::class, [
                'label' => "J'accepte les conditions générales de vente",
                'mapped' => false,
                'constraints' => new IsTrue(['message' => 'Vous devez accepter les conditions générales de vente.']),
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])

            
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => [
                    'class' => 'gap-2 col-12 mt-3 btn btn-primary'
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
