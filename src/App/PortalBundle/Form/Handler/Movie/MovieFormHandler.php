<?php
namespace App\PortalBundle\Form\Handler\Movie;

use App\PortalBundle\Services\ManagerService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use App\PortalBundle\Entity\Movie;

class MovieFormHandler
{
    private $message = "";

    /**
     * @var MovieFormHandlerStrategy
     */
    private $movieFormHandlerStrategy;

    /**
     * @var ManagerService
     */
    private $managerService;

    /**
     * @param MovieFormHandlerStrategy $mfhs
     */
    public function setMovieFormHandlerStrategy(MovieFormHandlerStrategy $mfhs)
    {
        $this->movieFormHandlerStrategy = $mfhs;
    }

    /**
     * @return MovieFormHandlerStrategy
     */
    public function getMovieFormHandlerStrategy()
    {
        return $this->movieFormHandlerStrategy;
    }

    public function setManagerService(ManagerService $managerService)
    {
        $this->managerService = $managerService;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function handleForm(FormInterface $form, Movie $movie, Request $request)
    {
        if (
            (null === $movie->getId() && $request->isMethod('POST'))
            || (null !== $movie->getId() && $request->isMethod('PUT'))
        ) {
            $originalHashTags = new ArrayCollection();

            // Create an ArrayCollection of the current Tag objects in the database
            foreach ($movie->getHashTags() as $tag) {
                $originalHashTags->add($tag);
            }

            $form->handleRequest($request);

            if (!$form->isValid()) {
                return false;
            }

            $this->message = $this->movieFormHandlerStrategy->handleForm($request, $movie, $originalHashTags);

            return true;
        }
    }

    public function handleSearchForm(FormInterface $form, Request $request)
    {
        $attributes = $request->attributes->all();

        foreach ($attributes as $key => $val) {
            if (!empty($val)) {
                // title, description, releaseDateFrom, releaseDateTo
                if (in_array($key, Movie::getLikeFieldsSearchForm())) {
                    $form->get($key)->setData($val);
                    continue;
                }

                // hashTags, actors
                if (in_array($key, Movie::getCollectionFields())) {
                    $normalizedKey = Movie::getManagerName($key);
                    $objectManager = $this->managerService->getManagerClass($normalizedKey . 'Manager');
                    foreach($val as $keyCollection => $valCollection) {
                        $attributes[$key][$keyCollection] = $objectManager->find($valCollection);
                    }
                    $form->get($key)->setData($attributes[$key]);
                    continue;
                }

                // category
                if (in_array($key, Movie::getObjectFields())) {
                    $objectManager = $this->managerService->getManagerClass($key . 'Manager');
                    $object = $objectManager->find($val);
                    $form->get($key)->setData($object);
                    continue;
                }
            }
        }
    }

    public function createForm(Movie $movie)
    {
        return $this->movieFormHandlerStrategy->createForm($movie);
    }

    public function createView()
    {
        return $this->movieFormHandlerStrategy->createView();
    }
}
