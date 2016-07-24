<?php

namespace App\PortalBundle\Form\Type;

use App\CoreBundle\Form\DataTransformer\TextToDateTimeDataTransformer;
use App\PortalBundle\Entity\Manager\Interfaces\MovieManagerInterface;
use App\PortalBundle\Entity\Movie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    /**
     *
     * @var MovieManagerInterface $handler
     */
    private $handler;

    /**
     * @param MovieManagerInterface $movieManager
     */
    public function __construct(MovieManagerInterface $movieManager)
    {
        $this->handler = $movieManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('title', TextType::class, array('label' => 'film.titre'))
            ->add('description', TextareaType::class, array('label' => 'film.description'))
            // if an image has previously been uploaded, we populate the movie object with database values
            ->add('image', ImageType::class, array('data' => $options['image']))
            ->add(
                $builder->create(
                    'releasedAt', TextType::class,
                    array(
                        'attr' => array('class' => 'datepicker', 'readonly' => true),
                        'label' => 'film.dateSortie',
                    )
                )
                    ->addModelTransformer(new TextToDateTimeDataTransformer())
            )

            ->add('category', 'genemu_jqueryselect2_entity', array(
                'class' => 'App\PortalBundle\Entity\Category',
                'property' => 'title',
                'multiple' => false,
                'required' => false,
                'label' => 'film.categorie',
                'configs' => array(
                    // Whether or not multiple values are allowed (default to false)
                    'multiple' => false
                )
            ))

            ->add('actors', 'genemu_jqueryselect2_entity', array(
                'class' => 'App\PortalBundle\Entity\Actor',
                'property' => 'firstNameLastName',
                'expanded' => false,
                'multiple' => true,
                'required' => false,
                'label' => 'film.acteurs',
                'configs' => array(
                    // Whether or not multiple values are allowed (default to false)
                    'multiple' => true
                )
            ));

        if (!empty($options) && isset($options['hashtags_hidden']) && !$options['hashtags_hidden']) {
            $builder->add('hashTags', HashTagCollectionType::class);
        }

        $builder->add('Valider', SubmitType::class, array(
            'attr' => ['class' => 'btn btn-primary btn-lg btn-block'],
            'label' => 'valider'
        ));

       $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                // object movie data from the form
                $data = $event->getData();

                if (!$data instanceof Movie) {
                    throw new \RuntimeException('Movie instance required.');
                }

                $dbMovie = null;
                if (null !== $data->getId()) {
                    $dbMovie = $this->handler->find($data->getId());
                }

                // if movie creation or no image in database for updated movie AND no file uploaded, we set image attribute to null
                if ((null === $dbMovie || null === $dbMovie->getImage()->getId()) &&
                    null === $event->getForm()->getData()->getImage()->getFile()
                ) {
                    $data->setImage(null);
                }


            }
        );
    }

    public function getName()
    {
        return 'app_portal_movie';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'App\PortalBundle\Entity\Movie',
                'hashtags_hidden' => true,
                'image' => null,
        ));
    }
}
