<?php
namespace App\PortalBundle\Form\Handler\Movie;

use App\PortalBundle\AppPortalEvents;
use App\PortalBundle\Event\MovieEvent;
use App\PortalBundle\Form\Type\MovieType;
use App\PortalBundle\Repository\Interfaces\MovieRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use App\PortalBundle\Entity\Movie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

class NewMovieFormHandlerStrategy extends AbstractMovieFormHandlerStrategy
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var MovieRepositoryInterface
     */
    protected $movieRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface $router
     */
    private $router;

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
     * @param TranslatorInterface $translator Service of translation
     * @param MovieRepositoryInterface $movieRepository
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param TokenStorageInterface $securityTokenStorage
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct
    (
        TranslatorInterface $translator,
        MovieRepositoryInterface $movieRepository,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        TokenStorageInterface $securityTokenStorage,
        EventDispatcherInterface $dispatcher

    )
    {
        $this->translator = $translator;
        $this->movieRepository = $movieRepository;
        $this->formFactory = $formFactory;
        $this->router = $router;
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

    public function handle(Request $request, Movie $movie)
    {
        $movie->setAuthor($this->securityTokenStorage->getToken()->getUser());
        $this->movieRepository->save($movie, true, true);

        $movieEvent = new MovieEvent($movie);
        $this->dispatcher->dispatch(AppPortalEvents::EVENT_MOVIE_1, $movieEvent);

        return $this->translator
            ->trans('film.ajouter.succes', array(
                '%titre%' => $movie->getTitle()
            ));
    }
}
