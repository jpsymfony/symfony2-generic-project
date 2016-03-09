<?php

namespace App\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text');
        $builder->add('email', 'email');
        $builder->add('password', 'repeated', array(
            'first_name'  => 'password',
            'second_name' => 'confirm',
            'type'        => 'password',
        ));
        $builder->add('Register', 'submit', array(
            'attr' => ['class' => 'btn btn-primary btn-lg btn-block']
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\UserBundle\Entity\Registration\Registration',
        ]);
    }

    public function getName()
    {
        return 'registration_form';
    }
} 
