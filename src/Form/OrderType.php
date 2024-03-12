<?php

namespace App\Form;

use App\Classe\Cart;
use App\Entity\Address;
use App\Entity\Order;
use App\Entity\TimeSlots;
use App\Entity\WorkingDay;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $timeSlots = $options['timeSlots'];
        $workingDays = $options['workingDays'];
        $user = $options['user'];
        

        // Ajouter le champ pour la sélection de l'adresse de livraison
        $builder->add('deliveryAddress', EntityType::class, [
            'label' => 'Votre adresse',
            'required' => true,
            'class' => Address::class,
            'choices' => $user->getAddresses(),
            'multiple' => false,
            'expanded' => true,
        ])

        // Ajouter le champ pour la sélection du jour de livraison
        ->add('deliveryDay', EntityType::class, [
            'label' => 'Choisissez votre jour de livraison',
            'class' => WorkingDay::class,
            'choices' => $workingDays,
            'choice_label' => 'dayOfWeek', // Assurez-vous que cette propriété existe dans votre entité WorkingDay
            'multiple' => false,
            'expanded' => true,
            // Évitez de laisser la sélection vide pour obliger l'utilisateur à choisir un jour
            
        ])

        // Ajouter le champ pour la sélection de l'heure de livraison
        ->add('deliveryHour', EntityType::class, [
            'label' => 'Choisissez votre heure de livraison',
            'class' => TimeSlots::class, // Assurez-vous que c'est la bonne classe pour les plages horaires
            'choices' => $timeSlots, // Cette liste sera remplie dynamiquement en fonction du jour choisi par l'utilisateur
            'choice_label' => 'hours', // Assurez-vous que cette propriété existe dans votre entité TimeSlots
            'multiple' => false,
            'expanded' => true, // Afficher les heures sous forme de radio buttons
        ])
        ->add('selected_formule', HiddenType::class, [
            // Assurez-vous que la valeur est correctement définie à partir des options du formulaire
            'data' => $options['selected_formule'],
        ])

        ->add('selected_products', HiddenType::class, [
            'data' => $options['selected_products'],        
            ])

        // Ajouter le champ pour le bouton de soumission
        ->add('submit', SubmitType::class, [
            'label' => 'Valider ma commande',
            'attr' => [
                'class' => 'mx-5 col-6 btn btn-primary'
            ],
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'user' => null,
            'workingDays' => [],
            'timeSlots' => [],
            'selected_formule' => [],
            'selected_products' => [],
        ]);
    }
}