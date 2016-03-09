<?php

namespace App\PortalBundle\Form\Type;

use App\CoreBundle\Form\DataTransformer\TextToDateTimeDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MovieFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title', 'text', array('label' => 'film.titre'))
                ->add('category', EntityType::class, array(
                    'class' => 'App\PortalBundle\Entity\Category',
                    'multiple' => false,
                    'required' => false,
                    'label' => 'film.categorie',
                    'empty_value' => 'film.categories.toutes',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.title', 'ASC');
                    },
                ))
                ->add('actors', EntityType::class, array(
                    'class' => 'App\PortalBundle\Entity\Actor',
                    'multiple' => true,
                    'required' => false,
                    'label' => 'film.acteurs',
                    'empty_value' => 'film.acteurs.tous',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.lastName', 'ASC');
                    },
                ))
                ->add('hashTags', EntityType::class, array(
                    'class' => 'App\PortalBundle\Entity\HashTag',
                    'multiple' => true,
                    'required' => false,
                    'label' => 'film.hashtags',
                    'empty_value' => 'film.prix.tous',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->orderBy('p.name', 'ASC');
                    },
                ))
                ->add('description', 'text', array(
                    'label' => 'film.description',
                ))
                ->add('releaseDateFrom', 'text',
                    array(
                        'attr' => array('class' => 'datepicker'),
                        'label' => 'film.dateSortieDebut',
                        'mapped' => false,
                    )
                )
                ->add('releaseDateTo', 'text',
                    array(
                        'attr' => array('class' => 'datepicker'),
                        'label' => 'film.dateSortieFin',
                        'mapped' => false,
                    )
                );
    }

    public function getName()
    {
        return 'app_portal_movie_filter';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'App\PortalBundle\Entity\Movie',
                'csrf_protection' => false,
        ));
    }
}
