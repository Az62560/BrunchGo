<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Formules;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PersonnalisationType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; 
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('category', EntityType::class, [
            //     'class' => Category::class,
            //     'mapped' => false, // Ne pas mapper à une entité
            //     'required' => false,
            // ])
            ->add('Produits', EntityType::class, [
                'label' => 'Sélectionnez vos produits',
                'class' => Product::class,
                'mapped' => false, // Ne pas mapper à une entité
                'multiple' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('formule');
        $resolver->setAllowedTypes('formule', Formules::class);
        $resolver->setRequired('category');
        $resolver->setAllowedTypes('category', Category::class);
        $resolver->setDefault('products', []);
    }
}
