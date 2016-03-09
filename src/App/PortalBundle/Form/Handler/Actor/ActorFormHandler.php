<?php
namespace App\PortalBundle\Form\Handler\Actor;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use App\PortalBundle\Entity\Actor;

class ActorFormHandler
{
    private $message = "";

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ActorFormHandlerStrategy $actorFormHandlerStrategy
     */
    protected $actorFormHandlerStrategy;

    public function setActorFormHandlerStrategy(ActorFormHandlerStrategy $afhs)
    {
        $this->actorFormHandlerStrategy = $afhs;
    }

    public function getActorFormHandlerStrategy()
    {
        return $this->actorFormHandlerStrategy;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function handleForm(FormInterface $form, Actor $actor, Request $request)
    {
        if (
            (null === $actor->getId() && $request->isMethod('POST'))
            || (null !== $actor->getId() && $request->isMethod('PUT'))
        ) {
            $form->submit($request);

            if (!$form->isValid()) {
                return false;
            }

            $this->message = $this->actorFormHandlerStrategy->handle($request, $actor);

            return true;
        }
    }

    public function createForm(Actor $movie)
    {
        return $this->actorFormHandlerStrategy->createForm($movie);
    }

    public function createView()
    {
        return $this->actorFormHandlerStrategy->createView();
    }
}
