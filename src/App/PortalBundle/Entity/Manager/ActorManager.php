<?php
namespace App\PortalBundle\Entity\Manager;

use App\CoreBundle\Entity\Manager\AbstractGenericManager;
use App\PortalBundle\Entity\Manager\Interfaces\ActorManagerInterface;
use App\PortalBundle\Repository\ActorRepository;

class ActorManager extends AbstractGenericManager implements ActorManagerInterface
{

    /**
     * ActorManager constructor.
     * @param ActorRepository $repository
     */
    public function __construct(ActorRepository $repository)
    {
        $this->repository = $repository;
    }
}
