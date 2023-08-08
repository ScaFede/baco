<?php

namespace App\Form;

use App\Entity\Scambi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ScambiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
      /*  ->add('status', HiddenType::class, [
           'attr' => ['hidden' => true],
       ])*/
            ->add('createdAt')
            ->add('statusString')
           /*->add('statusString', null, [
                'required'   => false,
                'empty_data' => 'John Doe',
            ])*/
            ->add('fromUser')
//            ->add('userTarget')

          /*   ->add('userTarget', TextType::class, [
                'data' => $options['userTarget'],
            ]);*/
            ->add('userTarget')

          /* per campi nascosti
            ->add('status', HiddenType::class, [
    'attr' => ['hidden' => true],
])*/
            //->add('myField', 'number', ['empty_data' => 'Default value'])
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Scambi::class,
          //  'userTarget' => null,
        ]);
    }
}
