<?php

namespace App\Controller\Admin;

use App\Entity\Scambi;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


use Symfony\Component\Security\Core\Security; //test get user


class ScambiCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Scambi::class;
    }


    public function __construct(private Security $security,){



    }


    public function configureFields(string $pageName): iterable
    {

      $fromUser =  $this->security->getUser();


        return [
           IdField::new('id')->onlyOnIndex(),
           Field::new('statusString'),
            Field::new('fromUser')->onlyOnIndex(),

            Field::new('scambioConfermato')->onlyOnIndex(),
            Field::new('confermaTarget')->onlyOnIndex(),
            Field::new('confermaSender')->onlyOnIndex(),
          //  Field::new('fromUser')->fromUser,
          //  $fromUser = $this->security->getUser(),

          // Choi::new('status')->setChoices(['started' => 'started','closed' => 'closed', ]),
           AssociationField::new('userSender'),
           AssociationField::new('userSenderCompetenzaRel'),

           AssociationField::new('userTarget'),
           AssociationField::new('userTargetCompetenzaRel'),

        ];
    }

}
