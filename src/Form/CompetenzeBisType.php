<?php

namespace App\Form;

use App\Entity\CompetenzeBis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


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
           ->add('categorieRelation', EntityType::class, [
               'class' => Categorie::class,
               'choice_label' => 'nome',
               'multiple' => true,
               'expanded' => false
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompetenzeBis::class,
        ]);
    }
}
