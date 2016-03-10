<?php
namespace App\PortalBundle\Entity\Manager;

use App\PortalBundle\Repository\Interfaces\ActorRepositoryInterface;

interface ActorManagerInterface
{
    /**
     * ActorManager constructor.
     * @param ActorRepositoryInterface $actorRepository
     */
    public function __construct(ActorRepositoryInterface $actorRepository);

    public function remove($entity);

    public function all($result = "object", $MaxResults = null, $orderby = '');

    public function find($actor);

    public function save($entity, $persist = false, $flush = true);
}
