<?php
namespace App\PortalBundle\Entity\Manager\Interfaces;

use App\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use App\PortalBundle\Repository\Interfaces\MovieRepositoryInterface;

interface MovieManagerInterface extends GenericManagerInterface
{
    /**
     * MovieManager constructor.
     * @param MovieRepositoryInterface $repository
     */
    public function __construct(MovieRepositoryInterface $repository);

    /**
     * @param int $limit
     * @param int $offset
     * @return array of movies
     */
    public function getFilteredMovies($limit = 20, $offset = 0);
}
