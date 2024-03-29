<?php

namespace App\Controller\Admin;

use App\Entity\TimeSlots;
use App\Entity\WorkingDay;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TimeSlotsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TimeSlots::class;
    }  

    
    public function configureFields(string $pageName): iterable
    {
        return [  
            TextField::new('hours', 'Heure'),
            BooleanField::new('isFree', 'Disponible'),
            // AssociationField::new('WorkingDay')     
        ];
    }
    
}
