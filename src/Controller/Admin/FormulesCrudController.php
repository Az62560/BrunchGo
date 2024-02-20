<?php

namespace App\Controller\Admin;

use App\Entity\Formules;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class FormulesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Formules::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'),
            ImageField::new('illustration')
                ->setBasePath('uploads/images')
                ->setUploadDir('public/uploads/images')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('subtitle'),
            TextEditorField::new('description'),
            BooleanField::new('isBest'),
            MoneyField::new('price')->setCurrency('EUR'),
            AssociationField::new('Category'),

        ];
    }
    
}
