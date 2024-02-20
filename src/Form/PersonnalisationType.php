<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Formules;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonnalisationType extends AbstractType
{
//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $formule = $options['formule'];
//         $category = $options['category'];
//         $allowedCategories = $formule->getCategory();

//         $builder
//             ->add('selected_category', EntityType::class, [
//                 'label' => 'Choisissez parmi chaques catégories',
//                 'class' => Category::class,
//                 'choice_label' => 'name',
//                 'choices' => $allowedCategories, // Utiliser les catégories associées à la formule
//                 'mapped' => false,
//                 'placeholder' => 'Sélectionnez une catégorie',
//                 'required' => false,
//             ])
//             ->add('selected_products', EntityType::class, [
//                 'label' => 'Choisissez parmi les produits',
//                 'class' => Product::class,
//                 'choice_label' => 'name',
//                 'choices' => $category->getProducts(),
//                 'multiple' => true,
//             ])
//         ;
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setRequired('formule'); // Définir l'option 'formule' comme requise
//         $resolver->setAllowedTypes('formule', Formules::class); // S'assurer que l'option 'formule' est de type Formules
//         $resolver->setDefaults([
          
//                 'formule' => null,
//                 'category' => null,
//                 'products' => null,
            
//         ]);
//     }
// }
// src/Form/PersonnalisationType.php


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formule = $options['formule'];
        $categories = $formule->getCategory();

        $builder
            ->add('selected_category', EntityType::class, [
                'label' => 'Choose from each category',
                'class' => Category::class,
                'choice_label' => 'name',
                'choices' => $categories,
                'mapped' => false,
                'placeholder' => 'Select a category',
                'required' => false,
            ])
            ->add('selected_products', EntityType::class, [
                'label' => 'Choose products',
                'class' => Product::class,
                'choice_label' => 'name',
                'choices' => $options['products'],
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('formule');
        $resolver->setAllowedTypes('formule', Formules::class);
        $resolver->setDefault('products', []);
    }
}
