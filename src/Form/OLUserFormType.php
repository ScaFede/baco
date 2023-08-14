<?php

namespace App\Form;


use App\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use App\Entity\Citta;
use App\Entity\CompetenzeBis;

class OLUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
      /*  ->add('status', HiddenType::class, [
           'attr' => ['hidden' => true],
       ])*/
       ->add('nickname', TextType::class, [
           'label' => 'Nickname',
           'required' => true,
       ])


       ->add('description', TextareaType::class, [
                       'label' => 'Descrizione',
                       'required' => false,
                   ])

     ->add('competenzeBisRel', EntityType::class, [
                     'class' => CompetenzeBis::class, // Utilizza la classe CompetenzeBis
                     'label' => 'Competenze',
                     'choice_label' => 'titolo', // Campo da mostrare nelle opzioni
                     'multiple' => true,
                     'expanded' => true,
                     'required' => false,
                 ])

     ->add('cittaRel', EntityType::class, [
                     'class' => Citta::class, // Utilizza la classe CompetenzeBis
                     'label' => 'Citta di provenienza',
                     'choice_label' => 'nome', // Campo da mostrare nelle opzioni
                     'multiple' => true,
                     'expanded' => true,
                     'required' => false,
                 ])


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
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
