<?php

namespace App\PortalBundle\Form\Handler\Actor;

use App\PortalBundle\Entity\Actor;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;


abstract class AbstractActorFormHandlerStrategy implements ActorFormHandlerStrategy
{

    /**
     * @var Form $form
     */
    protected $form;

    public function createView()
    {
        return $this->form->createView();
    }

    abstract public function handle(Request $request, Actor $movie);

    abstract public function createForm(Actor $movie);


}