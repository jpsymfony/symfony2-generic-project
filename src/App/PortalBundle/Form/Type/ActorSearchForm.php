<?php

namespace App\PortalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActorSearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('motcle', 'text', array('label' => 'motcle'))
                ->add('Rechercher', 'submit', array(
                            'attr' => ['class' => 'btn btn-lg btn-primary btn-block'],
                            'label' => 'rechercher'
                ));
    }
    
    public function getName()
    {
        return 'actor_search';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'divers',
            'csrf_protection' => false,
        ));
    }
}
