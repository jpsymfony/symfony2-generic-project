<?php

namespace App\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', array('label' => 'user.registration.username'));
        $builder->add('email', 'email');
        $builder->add('password', 'repeated', array(
            'first_name'  => 'password',
            'second_name' => 'confirm',
            'type'        => 'password',
            'first_options'  => array('label' => 'user.registration.password'),
            'second_options' => array('label' => 'user.registration.confirm'),
        ));
        $builder->add('Register', 'submit', array(
            'attr' => ['class' => 'btn btn-primary btn-lg btn-block'],
            'label' => 'user.registration.register'
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
