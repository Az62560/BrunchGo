<?php

namespace App\Controller\Admin;

use App\Entity\WorkingDay;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WorkingDayCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WorkingDay::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('dayOfWeek', 'Jours de livraison'),
            BooleanField::new('available', 'Disponible'),
            // DateField::new('dayOfWeek', 'Date de livraison'),
            AssociationField::new('timeSlots', 'Créneau horaire'),
        ];
    }
}
