<?php

namespace App\Form;

use App\Entity\Candidature;
use App\Entity\Entreprise;
use App\Entity\Entretien;
use App\Entity\Recruteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;


class EntretienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('horaire')
            ->add('lieu')
            ->add('confirmation')
            ->add('etat')
            ->add('cadidature',EntityType::class,[
                'class'=> Candidature::class,
                'choice_label'=>'id'
            ])
            ->add('recruteur',EntityType::class,[
                'class'=> Recruteur::class,
                'choice_label'=>'id'
            ])
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptchaUserRegistration',
                'constraints' => [
                    new ValidCaptcha([
                        'message' => 'Invalid captcha, please try again',
                    ])
                ])
                        )


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entretien::class,
        ]);
    }
}
