<?php

namespace App\PortalBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', array('required' => true, 'label' => 'contact.first_name'))
            ->add('lastName', 'text', array('required' => true, 'label' => 'contact.last_name'))
            ->add('cellphone', 'text', array('label' => 'contact.phone'))
            ->add('email', 'email', array('required' => true, 'label' => 'contact.email'))
            ->add('additionnalInformation', 'textarea', array('label' => 'contact.additional_information'))
            ->add('knowledge', 'choice', array(
                'choices'     => array(
                    'internet'         => 'contact.internet.knowledge',
                    'facebook'         => 'contact.facebook.knowledge',
                    'pub_papier'       => 'contact.pubpapier.knowledge',
                    'bouche_a_oreille' => 'contact.boucheaoreille.knowledge',
                    'presse_ecrite'    => 'contact.newspaper',
                    'reseaux_sociaux'  => 'contact.network',
                    'autre'            => 'contact.autre.knowledge',
                ),
                'required'    => false,
                'expanded'    => true,
                'multiple'    => false,
                'empty_value' => false
            ))
            ->add('other', 'text', array('label' => 'contact.autre'))
            ->add('Envoyer', 'submit', array(
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