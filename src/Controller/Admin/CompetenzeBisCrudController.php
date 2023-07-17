<?php

namespace App\Controller\Admin;

use App\Entity\CompetenzeBis;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;


class CompetenzeBisCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CompetenzeBis::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            Field::new('titolo'),
            Field::new('descrizione'),
            BooleanField::new('status_active'),
            Field::new('CreateAt'),
        //      DateTimeField::new('CreateAt')->setFormat('short', 'none'),
            AssociationField::new('UserRelation')->autocomplete(),

            // TextField::new('title'),
            // TextEditorField::new('description'),
        ];
    }




}
