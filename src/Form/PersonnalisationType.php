<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Formules;
use App\Entity\Product;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PersonnalisationType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; 
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {  
        $formules = $options['formules'];

        foreach ($formules as $formule) {
            $categories = $formule->getCategory();
            $formulePrice = $formule->getPrice();
            

            foreach ($categories as $category) {
                $builder
                ->add('formule_'.$formule->getId().'_category_'.$category->getId(), EntityType::class, [
                    'class' => 'App\Entity\Product',
                    'choices' => $category->getProducts(),
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => $category->getName(),
                ])
                ->add('price', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class, [
                    'data' => $formulePrice , // Value to pass
                    
                ]);
                
                
                
                
              
            }  
        }
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'formules' => [],
        ]);
    }
}




















    //     $builder
    //         ->add('Produits', EntityType::class, [
    //             'label' => 'Sélectionnez vos produits',
    //             'class' => Product::class,
    //             'mapped' => false, // Ne pas mapper à une entité
    //             'multiple' => true,
    //             'required' => false,
    //         ]);
    // }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setRequired('formule');
    //     $resolver->setAllowedTypes('formule', Formules::class);
    //     $resolver->setRequired('category');
    //     $resolver->setAllowedTypes('category', Category::class);
    //     $resolver->setDefault('products', []);