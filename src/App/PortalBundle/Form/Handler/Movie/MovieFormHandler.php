<?php
namespace App\PortalBundle\Form\Handler\Movie;

use App\PortalBundle\Entity\Manager\Interfaces\ActorManagerInterface;
use App\PortalBundle\Entity\Manager\Interfaces\CategoryManagerInterface;
use App\PortalBundle\Entity\Manager\Interfaces\HashTagManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use App\PortalBundle\Entity\Movie;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class MovieFormHandler
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
     * @var MovieFormHandlerStrategy
     */
    protected $movieFormHandlerStrategy;

    /**
     * @var CategoryManagerInterface
     */
    protected $categoryManager;

    /**
     * @var ActorManagerInterface
     */
    protected $actorManager;

    /** @var HashTagManagerInterface
     */
    protected $hashTagManager;

    public function setMovieFormHandlerStrategy(MovieFormHandlerStrategy $mfhs)
    {
        $this->movieFormHandlerStrategy = $mfhs;
    }

    public function getMovieFormHandlerStrategy()
    {
        return $this->movieFormHandlerStrategy;
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param CategoryManagerInterface $categoryManager
     */
    public function setCategoryManager($categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    /**
     * @param ActorManagerInterface $actorManager
     */
    public function setActorManager($actorManager)
    {
        $this->actorManager = $actorManager;
    }

    /**
     * @param HashTagManagerInterface $hashTagManager
     */
    public function setHashTagManager($hashTagManager)
    {
        $this->hashTagManager = $hashTagManager;
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

            $this->message = $this->movieFormHandlerStrategy->handle($request, $movie, $originalHashTags);

            return true;
        }
    }

    public function handleSearchForm(FormInterface $form, Request $request)
    {
        $attributes = $request->attributes->all();

        foreach ($attributes as $key => $val) {
            if (!empty($val)) {
                if (in_array($key, Movie::getLikeFieldsSearchForm())) { // title, description, releaseDateFrom, releaseDateTo
                    $form->get($key)->setData($val);
                    continue;
                }

                if (in_array($key, Movie::getCollectionFields())) { // hashTags, actors
                    $normalizedKey = $this->isValidClass($key);
                    $objectManager = $normalizedKey . 'Manager';
                    foreach($val as $keyCollection => $valCollection) {
                        $attributes[$key][$keyCollection] = $this->$objectManager->find($valCollection);
                    }
                    $form->get($key)->setData($attributes[$key]);
                    continue;
                }

                if (in_array($key, Movie::getObjectFields())) { // category
                    $key = $this->isValidClass($key);
                    $objectManager = $key . 'Manager';
                    $object = $this->$objectManager->find($val);
                    $form->get($key)->setData($object);
                    continue;
                }
            }
        }
    }

    private function isValidClass($class)
    {
        if (array_key_exists($class, Movie::getManagerCollectionMapping())) {
            $class = Movie::getManagerName($class); // get mappedClass, ie actor for actors, hashTag for hashTags, etc.
        }

        $nameSpaceClass = '\App\PortalBundle\Entity\Manager\\' . ucfirst($class) . 'Manager';
        if (!class_exists($nameSpaceClass)) {
            throw new ResourceNotFoundException('Impossible de trouver la classe ' . $nameSpaceClass);
        }

        return $class;
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
