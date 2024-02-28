<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Formules;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
            // $formulePrice = $formule->getPrice();
            

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

                // ->add('price', HiddenType::class, [
                //     'data' => $formulePrice , // Value to pass

                // ]);
                                       
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