<?php
namespace App\PortalBundle\Entity\Manager\Interfaces;

use App\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use App\PortalBundle\Repository\ActorRepository;

interface ActorManagerInterface extends GenericManagerInterface
{

    /**
     * ActorManager constructor.
     * @param ActorRepository $repository
     */
    public function __construct(ActorRepository $repository);

    /**
     * @param int $limit
     * @param int $offset
     * @return array of actors
     */
    public function getFilteredActors($limit = 20, $offset = 0);
}
