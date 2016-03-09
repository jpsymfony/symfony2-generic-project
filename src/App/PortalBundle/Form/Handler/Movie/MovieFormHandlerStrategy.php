<?php
namespace App\PortalBundle\Form\Handler\Movie;

use Symfony\Component\HttpFoundation\Request;
use App\PortalBundle\Entity\Movie;

interface MovieFormHandlerStrategy
{
    public function handle(Request $request, Movie $movie);

    public function createForm(Movie $movie);

    public function createView();
}
