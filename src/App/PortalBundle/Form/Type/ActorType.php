<?php

namespace App\PortalBundle\Form\Type;

use App\CoreBundle\Form\DataTransformer\TextToDateTimeDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('id', 'hidden')
        ->add('firstName', 'text', array('label' => 'acteur.nom'))
        ->add('lastName', 'text', array('label' => 'acteur.prenom'))
//        ->add('birthday', 'birthday', array('label' => 'acteur.dateNaissance'))
        ->add(
            $builder->create(
                'birthday', 'text',
                array(
                    'attr' => array('class' => 'datepicker'),
                    'label' => 'acteur.dateNaissance'
                )
            )
                ->addModelTransformer(new TextToDateTimeDataTransformer())
        )
        ->add('sex', 'choice', array(
            'choices' => array('F'=>'Féminin', 'M'=>'Masculin'),
            'label' => 'acteur.sexe'
            ))

        ->add('Valider', 'submit', array(
            'attr' => ['class' => 'btn btn-primary btn-lg btn-block'],
            'label' => 'valider'
        ));
    }

    public function getName()
    {
        return 'app_portal_actor';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'App\PortalBundle\Entity\Actor',
            )
        );
    }
}
