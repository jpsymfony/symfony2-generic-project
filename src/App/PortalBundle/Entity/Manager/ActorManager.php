<?php
namespace App\PortalBundle\Entity\Manager;

use App\PortalBundle\Repository\Interfaces\ActorRepositoryInterface;

class ActorManager
{
    /**
     * @var ActorRepositoryInterface
     */
    protected $actorRepository;

    /**
     * ActorManager constructor.
     * @param ActorRepositoryInterface $actorRepository
     */
    public function __construct(ActorRepositoryInterface $actorRepository)
    {
        $this->actorRepository = $actorRepository;
    }
    
    public function remove($entity)
    {
        $this->actorRepository->remove($entity);
    }
    
    public function all($result = "object", $MaxResults = null, $orderby = '')
    {
        return $this->actorRepository->findAllByEntity($result, $MaxResults, $orderby);
    }

    public function find($actor)
    {
        return $this->actorRepository->find($actor);
    }

    public function save($entity, $persist = false, $flush = true)
    {
        return $this->actorRepository->save($entity, $persist, $flush);
    }
}
