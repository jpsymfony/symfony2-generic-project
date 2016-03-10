<?php
namespace App\PortalBundle\Form\Handler\Movie;

use App\PortalBundle\AppPortalEvents;
use App\PortalBundle\Entity\Manager\HashTagManager;
use App\PortalBundle\Entity\Manager\MovieManager;
use App\PortalBundle\Event\MovieEvent;
use App\PortalBundle\Form\Type\MovieType;
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

class UpdateMovieFormHandlerStrategy extends AbstractMovieFormHandlerStrategy
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var MovieManager
     */
    protected $movieManager;

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
     * @param MovieManager $movieManager
     * @param HashTagManager $hashTagManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct
    (
        TranslatorInterface $translator,
        MovieManager $movieManager,
        HashTagManager $hashTagManager,
        AuthorizationCheckerInterface $authorizationChecker,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->translator = $translator;
        $this->movieManager = $movieManager;
        $this->hashTagManager = $hashTagManager;
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

    public function handle(Request $request, Movie $movie, ArrayCollection $originalHashTags = null)
    {
        if (!$this->authorizationChecker->isGranted(MovieVoter::EDIT, $movie)) {
            $errorMessage = $this->translator->trans('film.modifier.erreur', ['%movie%' => $movie->getTitle()]);

            throw new AccessDeniedException($errorMessage);
        }

        foreach ($originalHashTags as $hashTag) {
            if (false === $movie->getHashTags()->contains($hashTag)) {
                $movie->removeHashTag($hashTag);
                $this->hashTagManager->remove($hashTag);
            }
        }

        $this->movieManager->save($movie, false, true);

        $movieEvent = new MovieEvent($movie);
        $this->dispatcher->dispatch(AppPortalEvents::EVENT_MOVIE_1, $movieEvent);

        return $this->translator
            ->trans('film.modifier.succes', array(
                '%titre%' => $movie->getTitle()
            ));
    }
}
