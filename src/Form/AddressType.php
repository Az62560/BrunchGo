<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Quel nom souhaitez vous donner à l'adresse ?",
                'attr' => [
                    'placeholder' => 'Nommer votre adresse'
                ]
            ])

            ->add('firstname', TextType::class, [
                'label' => "Votre nom",
                'attr' => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])

            ->add('lastname', TextType::class, [
                'label' => "Votre prénom",
                'attr' => [
                    'placeholder' => 'Entrez votre prénom'
                ]
            ])

            ->add('address', TextType::class, [
                'label' => "Adresse",
                'attr' => [
                    'placeholder' => 'Entrez votre adresse'
                ]
            ])

            ->add('postal', TextType::class, [
                'label' => "Code postal",
                'attr' => [
                    'placeholder' => 'Entrez votre code postal'
                ]
            ])

            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr' => [
                    'placeholder' => 'Entrez votre ville'
                ]
            ])

            ->add('phone', TelType::class, [
                'label' => "Votre téléphone",
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Ajouter l'adresse",
                'attr' => [
                    'class' => 'd-grid gap-2 col-4 mx-auto btn btn-primary'                   
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
