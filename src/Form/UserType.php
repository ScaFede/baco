<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Citta;
use App\Entity\CompetenzeBis;
use App\Entity\Scambi;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
          /*  ->add('roles', null, [
                  'mapped' => false,
              ])
            ->add('password', null,[
                  'mapped' => false,
              ])*/
            ->add('nickname')
            ->add('description')
          /*  ->add('createAt',  null, [
                  'mapped' => false,
              ])*/
          ->add('avatar', FileType::class, [
                 'label' => 'Avatar',
                 'mapped' => false,
                 'required' => false,

                 'constraints' => [
                                    new File([
                                        'maxSize' => '1024k',
                                       /*'mimeTypes' => [
                                            'application/pdf',
                                            'application/x-pdf',
                                        ],*/
                                       // 'mimeTypesMessage' => 'Please upload a valid PDF document',
                                    ])
                                ],



             ])
          /*  ->add('scambiConclusi', null, [
                  'mapped' => false,
              ])*/
            // ->add('CompetenzeBisRel', null, [
            //       'mapped' => false,
            //   ])


              ->add('CompetenzeBisRel', EntityType::class, [
                              'class' => CompetenzeBis::class, // Utilizza la classe CompetenzeBis
                              'label' => 'Competenze',
                              'choice_label' => 'titolo', // Campo da mostrare nelle opzioni
                              'multiple' => true,
                              'expanded' => false,
                              'required' => false,
                          ])

              ->add('cittaRel', EntityType::class, [
                'class' => Citta::class,
                'label' => 'Città di provenienza',
                'choice_label' => 'nome', // Il campo da mostrare nelle opzioni
                'multiple' => false, // Seleziona una sola città
                'required' => false,

            ])
          /*  ->add('ScambiUser', null, [
                  'mapped' => false,
              ])
            ->add('cittaRel')*/

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
