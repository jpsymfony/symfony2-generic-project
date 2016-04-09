<?php
namespace App\PortalBundle\Entity\Manager;

use App\CoreBundle\Entity\Manager\AbstractGenericManager;
use App\PortalBundle\Entity\Manager\Interfaces\ManagerInterface;
use App\PortalBundle\Entity\Manager\Interfaces\MovieManagerInterface;
use App\PortalBundle\Repository\Interfaces\MovieRepositoryInterface;

class MovieManager extends AbstractGenericManager implements MovieManagerInterface, ManagerInterface
{
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

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return 'movieManager';
    }
}
