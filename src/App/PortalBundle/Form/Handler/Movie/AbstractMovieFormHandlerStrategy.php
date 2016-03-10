<?php

namespace App\PortalBundle\Form\Handler\Movie;

use App\PortalBundle\Entity\Movie;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractMovieFormHandlerStrategy implements MovieFormHandlerStrategy
{

    /**
     * @var Form
     */
    protected $form;

    public function createView()
    {
        return $this->form->createView();
    }

    abstract public function handle(Request $request, Movie $movie, ArrayCollection $originalTags = null);

    abstract public function createForm(Movie $movie);


}