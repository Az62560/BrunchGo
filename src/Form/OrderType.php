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

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $workingDays = $options['workingDays'];

        $builder
            ->add('addresses', EntityType::class, [
                'label' => 'Votre adresse',
                'required' => true,
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true
            ])
            ->add('workingDay', EntityType::class, [
                'label' => "Sélectionnez le jour de livraison :",
                'required' => true,
                'class' => WorkingDay::class,
                'choices' => $workingDays,
                'choice_label' => function ($workingDay) {
                    return $workingDay->getDay(); // Ajoutez une méthode getDay() dans votre entité WorkingDay
                },
                'placeholder' => 'Choisir un jour',
                'mapped' => false,
            ])
            ->add('timeslot', EntityType::class, [
                'label' => "Votre heure de livraison :",
                'required' => true,
                'class' => TimeSlots::class,
                'placeholder' => 'Sélectionnez d\'abord un jour',
                'choices' => [],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande et payer', 
                'attr' => [
                    'class' => 'mx-5 col-6 btn btn-primary'
                ]
            ]);
        
        $builder->get('workingDay')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm()->getParent();
                $workingDay = $event->getForm()->getData();
                $form->add('timeslot', EntityType::class, [
                    'label' => "Votre heure de livraison :",
                    'required' => true,
                    'class' => TimeSlots::class,
                    'choices' => $workingDay->getTimeSlots(),
                    'choice_label' => function ($timeSlot) {
                        return $timeSlot->getStartTime()->format('H:i') . ' - ' . $timeSlot->getEndTime()->format('H:i');
                    },
                    'placeholder' => 'Sélectionnez une heure',
                    'mapped' => false,
                ]);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'user' => null,
            'workingDays' => [],
        ]);
    }
}
