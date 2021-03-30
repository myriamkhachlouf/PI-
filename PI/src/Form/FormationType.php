<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Formation;
use App\Entity\Formateur;
use App\Entity\Entreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference')
            ->add('periode')
            ->add('objectif')
            ->add('duree')
            ->add('capacite')
            ->add('rating')
            ->add('entreprise',EntityType::class,[
                'class'=> Entreprise::class,
                'choice_label'=>'nom'
            ])
            ->add('formateur',EntityType::class,[
                'class'=> Formateur::class,
                'choice_label'=>'prenom'
            ])
            ->add('idevenement',EntityType::class,[
        'class'=> Evenement::class,
        'choice_label'=>'nom'
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
