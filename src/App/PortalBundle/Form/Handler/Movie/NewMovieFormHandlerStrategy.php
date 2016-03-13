<?php
namespace App\PortalBundle\Form\Handler\Movie;

use App\PortalBundle\AppPortalEvents;
use App\PortalBundle\Event\MovieEvent;
use App\PortalBundle\Form\Type\MovieType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use App\PortalBundle\Entity\Movie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NewMovieFormHandlerStrategy extends AbstractMovieFormHandlerStrategy
{
    /**
     * @var TokenStorageInterface
     */
    protected $securityTokenStorage;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param TokenStorageInterface $securityTokenStorage
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct
    (
        TokenStorageInterface $securityTokenStorage,
        EventDispatcherInterface $dispatcher

    )
    {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->dispatcher = $dispatcher;
    }

    public function createForm(Movie $movie)
    {
        $this->form = $this->formFactory->create(new MovieType(), $movie, array(
            'action' => $this->router->generate('movie_new'),
            'method' => 'POST',
            'category_hidden' => false,
            'actors_hidden' => false,
            'hashtags_hidden' => false,
        ));

        return $this->form;
    }

    public function handle(Request $request, Movie $movie, ArrayCollection $originalHashTags = null)
    {
        $movie->setAuthor($this->securityTokenStorage->getToken()->getUser());
        $this->movieManager->save($movie, true, true);

        $movieEvent = new MovieEvent($movie);
        $this->dispatcher->dispatch(AppPortalEvents::EVENT_MOVIE_1, $movieEvent);

        return $this->translator
            ->trans('film.ajouter.succes', array(
                '%titre%' => $movie->getTitle()
            ));
    }


}
