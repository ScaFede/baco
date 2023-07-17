<?php

namespace App\Controller\Admin;

use App\Entity\User;


use EasyCorp\Bundle\EasyAdminBundle\Config\{Action, Actions, Crud, KeyValueStore};
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;


use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\{FormBuilderInterface, FormEvent, FormEvents};

// use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;


class UserCrudController extends AbstractCrudController
{


  public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher
    ) {}





    public static function getEntityFqcn(): string
    {
        return User::class;
    }








    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ;
    }











   public function configureFields(string $pageName): iterable
    {
        return [
          IdField::new('id')->onlyOnIndex(),
          Field::new('description'),
          Field::new('email'),
          ChoiceField::new('roles')->setChoices(['ROLE_ADMIN' => 'ROLE_ADMIN','ROLE_USER' => 'ROLE_USER', ])->allowMultipleChoices(),
          AssociationField::new('competenzeRel')->autocomplete(),
          AssociationField::new('CompetenzeBisRel')->autocomplete(),
          Field::new('nickname'),
          //Field::new('password')->onlyOnForms(),
          Field::new('password')->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => '(Repeat)'],
                'mapped' => false,
            ])
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms(),
          Field::new('createAt'),


          // 'id',
          // 'description',
          // 'email',
          // 'nickname',
          // 'password',
          // 'createAt',

          //roles[]
        //    IdField::new('id'),
        //    TextField::new('title'),
        //    TextEditorField::new('description'),
          //  ArrayField::new('roles')->setChoices(['ROLE_USER' => 'ROLE_USER'])->allowMultipleChoices()
        /*  yield IdField::new('id'),
          yield EmailField::new('email'),
          yield TextField::new('nickname'),
          yield TextEditorField::new('description'),
          yield ChoiceField::new('roles')->setChoices(['ROLE_ADMIN' => 'ROLE_ADMIN','ROLE_USER' => 'ROLE_USER', ])->allowMultipleChoices(),
          yield DateTimeField::new('create_at'),
          yield TextField::new('password')->setFormType(PasswordType::class)
          */

        ];

    }





















    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword() {
        return function($event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            $hash = $this->userPasswordHasher->hashPassword($form->getData(), $password);
            $form->getData()->setPassword($hash);
        };
    }









}
