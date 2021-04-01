<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use App\Entity\Users;
use App\Entity\Candidature;
use App\Entity\Entretien;
use App\Entity\Offre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_candidature', DateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('Pdf',FileType::class,array('label'=>'inserer un PDF',
                'data_class' => null))
            ->add('candidat',EntityType::class,[
                'class'=> Users::class,
                'choice_label'=>'nom'
            ])
            ->add('offre',EntityType::class,[
                'class'=> Offre::class,
                'choice_label'=>'nom_offre'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
