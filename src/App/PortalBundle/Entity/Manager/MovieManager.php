<?php
namespace App\PortalBundle\Entity\Manager;

use App\PortalBundle\Repository\Interfaces\MovieRepositoryInterface;

class MovieManager
{
    /**
     * @var MovieRepositoryInterface
     */
    protected $movieRepository;

    /**
     * MovieManager constructor.
     * @param MovieRepositoryInterface $movieRepository
     */
    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }
    
    public function remove($entity)
    {
        $this->movieRepository->remove($entity);
    }
    
    public function all($result = "object", $MaxResults = null, $orderby = '')
    {
        return $this->movieRepository->findAllByEntity($result, $MaxResults, $orderby);
    }
}
