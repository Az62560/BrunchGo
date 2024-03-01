<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\TimeSlots;
use App\Entity\WorkingDay;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class OrderType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $workingDays = $options['workingDays'];
        $user = $options['user'];
        // Initialiser un tableau pour stocker les choix d'horaires de TimeSlots
        $timeSlotChoices = [];
        $builder->add(
            'addresses',
            EntityType::class,
            [
                'label' => 'Votre adresse',
                'required' => true,
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true,
                ]
            );
            foreach ($workingDays as $workingDay) {
                $timeSlots = $workingDay->getTimeSlots();
                
                // Ajouter les horaires de TimeSlots aux choix
                
                
                // Ajouter le champ pour le jour de la semaine
                $builder->add('workingDay_' . $workingDay->getId(), EntityType::class, [
                    'label' => $workingDay->getDayOfWeek(),
                    'class' => 'App\Entity\TimeSlots',
                    'choices' => $timeSlots,
                    'choice_label' => 'hours',
                    'multiple' => false,
                    'expanded' => false,
                ]);
            }
            
            // Ajouter le champ pour les horaires de TimeSlots
            
            
            
            // Ajouter le champ pour le bouton de soumission
            $builder->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande et payer',
                'attr' => [
                    'class' => 'mx-5 col-6 btn btn-primary'
                    ]
                ]);
            }
            public function configureOptions(OptionsResolver $resolver): void
            {
                $resolver->setDefaults([
                    'data_class' => Order::class,
                    'user' => null,
                    'workingDays' => [],
                    'timeSlots' => [],
                ]);
            }
        }
