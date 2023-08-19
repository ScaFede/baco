<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use App\Entity\CompetenzeBis;
use App\Entity\Citta;
use App\Entity\UserConoscenzeImage;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;



class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')


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
                          'expanded' => false,
                          'required' => false,
                      ])

          ->add('cittaRel', EntityType::class, [
            'class' => Citta::class,
            'label' => 'Città di provenienza',
            'choice_label' => 'nome', // Il campo da mostrare nelle opzioni
            'multiple' => false, // Seleziona una sola città
            'required' => false,
            'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
              return $er->createQueryBuilder('c')
                  ->orderBy('c.nome', 'ASC'); // Ordina in modo alfabetico
          },

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

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])


          /*  ->add('conoscenzeImages', CollectionType::class, [
                'entry_type' => FileType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Carica immagini delle tue competenze (max 6)',
                'mapped' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'multiple' => true,
                ],
            ])*/

          ->add('conoscenzeImages', FileType::class, [
             'label' => 'Carica immagini delle competenze (max 6)',
             'required' => false,
             'multiple' => true,
             'mapped' => false,
             'attr' => [
                 'accept' => 'image/*',
             ],
         ])





            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);





    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
