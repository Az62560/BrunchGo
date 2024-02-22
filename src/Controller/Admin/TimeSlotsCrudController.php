<?php

namespace App\Controller\Admin;

use App\Entity\TimeSlots;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimezoneField;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

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
            BooleanField::new('isFree', 'Disponible')     
        ];
    }
    
}
