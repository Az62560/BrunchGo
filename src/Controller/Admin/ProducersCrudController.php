<?php

namespace App\Controller\Admin;

use App\Entity\Producers;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProducersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Producers::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom du producteur'),
            TextEditorField::new('description', "Description de l'entreprise ou du producteur"),
            TextareaField::new('address', 'Adresse du producteur')

        ];
    }
    
}
