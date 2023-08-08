<?php

namespace App\Form;

use App\Entity\Scambi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('fromUser')
            ->add('userTarget')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Scambi::class,
        ]);
    }
}
