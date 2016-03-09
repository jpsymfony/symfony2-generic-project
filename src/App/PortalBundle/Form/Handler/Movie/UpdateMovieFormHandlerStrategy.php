<?php
namespace App\PortalBundle\Form\Handler\Movie;

use App\PortalBundle\AppPortalEvents;
use App\PortalBundle\Event\MovieEvent;
use App\PortalBundle\Form\Type\MovieType;
use App\PortalBundle\Repository\Interfaces\MovieRepositoryInterface;
use App\UserBundle\Security\MovieVoter;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\PortalBundle\Entity\Movie;
use App\PortalBundle\Entity\Manager\HashTagManager;

class UpdateMovieFormHandlerStrategy extends AbstractMovieFormHandlerStrategy
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
     * @var HashTagManager
     */
    protected $hashTagManager;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator Service of translation
     * @param MovieRepositoryInterface $movieRepository
     * @param HashTagManager $hashTagManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct
    (
        TranslatorInterface $translator,
        MovieRepositoryInterface $movieRepository,
        HashTagManager $hashTagManager,
        AuthorizationCheckerInterface $authorizationChecker,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->translator = $translator;
        $this->movieRepository = $movieRepository;
        $this->hashtagManager = $hashTagManager;
        $this->authorizationChecker = $authorizationChecker;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->dispatcher = $dispatcher;
    }

    public function createForm(Movie $movie)
    {
        // we put image in the constructor of MovieType to fill value when the form is loaded
        $this->form = $this->formFactory->create(new MovieType($movie->getImage()), $movie, array(
            'action' => $this->router->generate('movie_edit', array('id' => $movie->getId())),
            'method' => 'PUT',
            'category_hidden' => false,
            'actors_hidden' => false,
            'hashtags_hidden' => false,
        ));

        return $this->form;
    }

    public function handle(Request $request, Movie $movie)
    {
        if (!$this->authorizationChecker->isGranted(MovieVoter::EDIT, $movie)) {
            $errorMessage = $this->translator->trans('film.modifier.erreur', ['%movie%' => $movie->getTitle()]);

            throw new AccessDeniedException($errorMessage);
        }

        $originalHashTags = new ArrayCollection();

        // we get hashTags for selected movie
        foreach ($movie->getHashTags() as $hashTag) {
            $originalHashTags->add($hashTag);
        }

        foreach ($originalHashTags as $hashTag) {
            // we check if hashTags from request are the same than edited movie to remove them if necessary
            if (false === $movie->getHashTags()->contains($hashTag)) {
                $hashTag->getMovie()->removeHashTag($hashTag);
                $this->hashTagManager->remove($hashTag);
            }
        }

        $this->movieRepository->save($movie, false, true);

        $movieEvent = new MovieEvent($movie);
        $this->dispatcher->dispatch(AppPortalEvents::EVENT_MOVIE_1, $movieEvent);

        return $this->translator
            ->trans('film.modifier.succes', array(
                '%titre%' => $movie->getTitle()
            ));
    }
}
