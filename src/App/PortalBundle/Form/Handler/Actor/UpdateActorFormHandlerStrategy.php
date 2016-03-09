<?php
namespace App\PortalBundle\Form\Handler\Actor;

use App\PortalBundle\Form\Type\ActorType;
use App\PortalBundle\Repository\Interfaces\ActorRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use App\PortalBundle\Entity\Actor;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\FormFactoryInterface;

class UpdateActorFormHandlerStrategy extends AbstractActorFormHandlerStrategy
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var ActorRepositoryInterface
     */
    protected $actorRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator Service of translation
     * @param ActorRepositoryInterface $actorRepository
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     */
    public function __construct
    (
        TranslatorInterface $translator,
        ActorRepositoryInterface $actorRepository,
        FormFactoryInterface $formFactory,
        RouterInterface $router
    )
    {
        $this->translator = $translator;
        $this->actorRepository = $actorRepository;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function createForm(Actor $actor)
    {
        $this->form = $this->formFactory->create(new ActorType(), $actor, array(
            'action' => $this->router->generate('actor_edit', array('id' => $actor->getId())),
            'method' => 'PUT',
        ));

        return $this->form;
    }

    public function handle(Request $request, Actor $actor)
    {
        $this->actorRepository->save($actor, false, true);

        return $this->translator
            ->trans('acteur.modifier.succes', array(
                '%nom%' => $actor->getFirstName(),
                '%prenom%' => $actor->getLastName()
            ));
    }
}
