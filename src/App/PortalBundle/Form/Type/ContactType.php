<?php

namespace App\PortalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array('required' => true, 'label' => 'contact.first_name'))
            ->add('lastName', TextType::class, array('required' => true, 'label' => 'contact.last_name'))
            ->add('cellphone', TextType::class, array('label' => 'contact.phone'))
            ->add('email', EmailType::class, array('required' => true, 'label' => 'contact.email'))
            ->add('additionnalInformation', TextareaType::class, array('label' => 'contact.additional_information'))
            ->add('knowledge', ChoiceType::class, array(
                'choices'     => array(
                    'internet'         => 'contact.internet.knowledge',
                    'facebook'         => 'contact.facebook.knowledge',
                    'pub_papier'       => 'contact.pubpapier.knowledge',
                    'bouche_a_oreille' => 'contact.boucheaoreille.knowledge',
                    'presse_ecrite'    => 'contact.newspaper.knowledge',
                    'reseaux_sociaux'  => 'contact.network.knowledge',
                    'autre'            => 'contact.autre.knowledge',
                ),
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
            ))
            ->add('other', TextType::class, array('label' => 'contact.autre'))
            ->add('Envoyer', SubmitType::class, array(
                'attr' => ['class' => 'btn btn-primary btn-lg btn-block'],
                'label' => 'contact.validation_button'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\PortalBundle\Entity\Contact',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'app_portal_contacttype';
    }

}