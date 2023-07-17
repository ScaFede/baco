<?php

namespace App\Controller\Admin;

use App\Entity\Competenze;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CompetenzeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Competenze::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            Field::new('titolo'),
            Field::new('descrizione'),

            AssociationField::new('userRel')->autocomplete(),
          //  AssociationField::new('userRel')->autocomplete(),
            BooleanField::new('status'),
            Field::new('createAt'),
        ];
    }

}
