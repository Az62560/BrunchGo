<?php

namespace App\Form;

use App\Classe\Cart;
use App\Entity\Address;
use App\Entity\DaySchedule;
use App\Entity\Order;
use App\Entity\TimeSlots;
use App\Entity\WorkingDay;
use DateTime;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // // Récupère les jours de la semaine
        // $daysOfWeek = [
        //     1 => 'Lundi',
        //     2 => 'Mardi',
        //     3 => 'Mercredi',
        //     4 => 'Jeudi',
        //     5 => 'Vendredi', // Utilisez 5 pour Vendredi
        //     6 => 'Samedi',   // Utilisez 6 pour Samedi
        //     7 => 'Dimanche', // Utilisez 7 pour Dimanche
        // ];

        // // Définir la fonction pour calculer la date pour chaque jour de la semaine
        // $calculateDates = function() use ($daysOfWeek) {
        //     $currentDate = new DateTime();
        //     $nextDays = [];
        //     for ($i = 2; $i <= 7; $i++) {
        //         $nextDays[$currentDate->format('d-m-Y')] = $currentDate->format('D, d M Y');
        //         $currentDate->modify('+1 day');     
        //     }
        //     return $nextDays;
            

     
        // };

        $timeSlots = $options['timeSlots'];
  
        $builder
            ->add('deliveryAddress', EntityType::class, [
                'label' => 'Votre adresse',
                'required' => true,
                'class' => Address::class,
                'choices' => $options['user']->getAddresses(),
                'multiple' => false,
                'expanded' => true,
            ])
            // ->add('deliveryDate', ChoiceType::class, [
            //     'label' => 'Choisissez la date de livraison',
            //     'choices' => $calculateDates(),
            //     'data' => ($calculateDates()), // Par défaut, utilise la première date dans la liste des prochains jours
            // ])
            // ->add('deliveryDay', EntityType::class, [
            //     'label' => 'Choisissez votre jour de livraison',
            //     'class' => DaySchedule::class,
            //     'choices' => $options['daySchedule'],
            //     'choice_label' => function ($daySchedule) use ($daysOfWeek) {
            //         $dayOfWeek = $daySchedule->getDayOfWeek();
            //         $available = $daySchedule->isIsWork();
        
            //         if ($available) {
            //             return $daysOfWeek[$dayOfWeek];
            //         } else {
            //             return $daysOfWeek[$dayOfWeek] . ' (indisponible)';
            //         }
            //     },
            //     'multiple' => false,
            //     'expanded' => true,
            // ])
            ->add('deliveryHour', EntityType::class, [
                'label' => 'Choisissez votre heure de livraison',
                'class' => TimeSlots::class, // Assurez-vous que c'est la bonne classe pour les plages horaires
                'choices' => $timeSlots, // Cette liste sera remplie dynamiquement en fonction du jour choisi par l'utilisateur
                'choice_label' => 'hours', // Assurez-vous que cette propriété existe dans votre entité TimeSlots
                'multiple' => false,
                'expanded' => true, // Afficher les heures sous forme de radio buttons
            ])

        // // // Ajouter le champ pour la sélection de l'heure de livraison
        // ->add('deliveryHour', EntityType::class, [
        //     'label' => 'Choisissez votre heure de livraison',
        //     'class' => TimeSlots::class, // Assurez-vous que c'est la bonne classe pour les plages horaires
        //     'choices' => , // Cette liste sera remplie dynamiquement en fonction du jour choisi par l'utilisateur
        //     'choice_label' => 'hours', // Assurez-vous que cette propriété existe dans votre entité TimeSlots
        //     'multiple' => false,
        //     'expanded' => true,
        //     // Évitez de laisser la sélection vide pour obliger l'utilisateur à choisir un jour
        // ])

        // Ajouter le champ pour l'heure de livraison
        // ->add('deliveryTime', TimeType::class, [
        //     'label' => 'Choisissez l\'heure de livraison',
        //     'widget' => 'choice',
        //     'input' => 'string',
        //     'placeholder' => [
        //         'hour' => 'Heure', 'minute' => 'Minute'
        //     ],
        //     'hours' => range(11, 16),
        //     'minutes' => range(0, 30, 30),
        // ])

        // Ajouter le champ pour la formule sélectionnée
        ->add('selected_formule', HiddenType::class, [
            // Assurez-vous que la valeur est correctement définie à partir des options du formulaire
            'data' => $options['selected_formule'],
        ])

        // Ajouter le champ pour les produits sélectionnés
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
            // 'workingDays' => [],
            'timeSlots' => [],
            'selected_formule' => [],
            'selected_products' => [],
            'daySchedule' => [],
        ]);
    }
}
