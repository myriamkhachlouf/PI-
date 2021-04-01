<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\Stage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_debut', DateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('date_fin', DateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('type_du_stage')
            ->add('nom_encadrant')
            ->add('offre',EntityType::class,[
                'class'=> Offre::class,
                'choice_label'=>'nom_offre'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}
