<?php

namespace App\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
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
        $formules = $options['formules'];

        foreach ($formules as $formule) {
            $categories = $formule->getCategory();
                foreach ($categories as $category) {
                $builder
                ->add('formule_'.$formule->getId().'_category_'.$category->getId(), EntityType::class, [
                    'class' =>'App\Entity\Product',
                    'choices' => $category->getProducts(),
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => $category->getName(),                  
                ]);                                    
            }              
        };
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'formules' => [],
        ]);
    }
}