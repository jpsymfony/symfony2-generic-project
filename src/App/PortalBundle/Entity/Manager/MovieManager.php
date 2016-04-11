<?php
namespace App\PortalBundle\Entity\Manager;

use App\CoreBundle\Entity\Manager\AbstractGenericManager;
use App\PortalBundle\Entity\Manager\Interfaces\ManagerInterface;
use App\PortalBundle\Entity\Manager\Interfaces\MovieManagerInterface;
use App\PortalBundle\Entity\Movie;
use App\PortalBundle\Repository\Interfaces\MovieRepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Routing\RouterInterface;

class MovieManager extends AbstractGenericManager implements MovieManagerInterface, ManagerInterface
{
    /**
     * @var FormTypeInterface
     */
    protected $searchFormType;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var RouterInterface $router
     */
    protected $router;

    /**
     * MovieManager constructor.
     * @param MovieRepositoryInterface $repository
     */
    public function __construct(MovieRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    public function getFilteredMovies($limit = 20, $offset = 0)
    {
        return $this->repository->getMovies($limit, $offset);
    }

    public function getMovieSearchForm(Movie $movie)
    {
        return $this->formFactory->create(
            $this->searchFormType,
            $movie,
            [
                'action' => $this->router->generate('movie_search'),
                'method' => 'GET',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function setSearchFormType(FormTypeInterface $searchFormType)
    {
        $this->searchFormType = $searchFormType;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setFormFactory($formFactory)
    {
        $this->formFactory = $formFactory;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setRouter($router)
    {
        $this->router = $router;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return 'movieManager';
    }
}
