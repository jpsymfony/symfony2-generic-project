<?php
namespace App\PortalBundle\Entity\Manager;

use App\PortalBundle\Repository\Interfaces\MovieRepositoryInterface;

interface MovieManagerInterface
{
    /**
     * MovieManager constructor.
     * @param MovieRepositoryInterface $movieRepository
     */
    public function __construct(MovieRepositoryInterface $movieRepository);
    
    public function remove($entity);
    
    public function all($result = "object", $MaxResults = null, $orderby = '');

    public function save($entity, $persist = false, $flush = true);
}
