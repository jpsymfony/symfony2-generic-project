<?php
namespace App\PortalBundle\Entity\Manager;

use App\PortalBundle\Repository\Interfaces\MovieRepositoryInterface;

class MovieManager implements MovieManagerInterface
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

    public function save($entity, $persist = false, $flush = true)
    {
        return $this->movieRepository->save($entity, $persist, $flush);
    }
}
