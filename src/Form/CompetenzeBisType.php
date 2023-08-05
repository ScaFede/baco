<?php

namespace App\Form;

use App\Entity\CompetenzeBis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetenzeBisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titolo')
            ->add('Descrizione')
            ->add('StatusActive')
            ->add('CreateAt')
            ->add('UserRelation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompetenzeBis::class,
        ]);
    }
}
