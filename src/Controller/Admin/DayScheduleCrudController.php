<?php

namespace App\Controller\Admin;

use App\Entity\DaySchedule;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use phpDocumentor\Reflection\Types\Integer;

class DayScheduleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DaySchedule::class;
    }
    
    
    public function configureFields(string $pageName): iterable
    {
        return [
            ChoiceField::new('dayOfWeek', 'Jour de la semaine')
            ->setChoices([
                'Lundi' => 1,
                'Mardi' => 2,
                'Mercredi' => 3,
                'Jeudi' => 4,
                'Vendredi' => 5,
                'Samedi' => 6,
                'Dimanche' => 7,
            ]),
            TimeField::new('startTime', 'Heure de début de service'),
            TimeField::new('endTime', 'Heure de fin de service'),
            BooleanField::new('isWork'),
        ];
    } 
}
