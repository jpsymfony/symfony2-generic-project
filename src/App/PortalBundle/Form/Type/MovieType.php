<?php

namespace App\PortalBundle\Form\Type;

use App\CoreBundle\Form\DataTransformer\TextToDateTimeDataTransformer;
use App\PortalBundle\Entity\Image;
use App\PortalBundle\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    /**
     * @var Image
     */
    private $image;

    public function __construct(Image $image = null)
    {
        if (null !== $image) {
            $this->image = $image;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('title', 'text', array('label' => 'film.titre'))
            ->add('description', 'textarea', array('label' => 'film.description'))
            ->add('image', new ImageType(), array('data' => $this->image))// if an image has previously been uploaded, we populate the movie object with database values
            ->add(
                $builder->create(
                    'releaseAt', 'text',
                    array(
                        'attr' => array('class' => 'datepicker'),
                        'label' => 'film.dateSortie'
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
                    'multiple' => false // Whether or not multiple values are allowed (default to false)
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
                    'multiple' => true // Whether or not multiple values are allowed (default to false)
                )
            ));

        if (!empty($options)) {
            if (isset($options['hashtags_hidden']) && !$options['hashtags_hidden']) {
                $builder->add('hashTags', 'hashtags');
            }
        }

        $builder->add('Valider', 'submit', array(
            'attr' => ['class' => 'btn btn-primary btn-lg btn-block'],
            'label' => 'valider'
        ));

       $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData(); // object movie data from the form

                if (!$data instanceof Movie) {
                    throw new \RuntimeException('Movie instance required.');
                }

                // if no image in database and no file uploaded, we set image attribute to null
                if (null === $this->image && null === $event->getForm()->getData()->getImage()->getFile()) {
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
        ));
    }
}
