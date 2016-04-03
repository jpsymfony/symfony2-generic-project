<?php
namespace App\PortalBundle\Entity\Manager;

use App\CoreBundle\Entity\Manager\AbstractGenericManager;
use App\PortalBundle\Entity\Manager\Interfaces\ManagerInterface;
use App\PortalBundle\Entity\Manager\Interfaces\MovieManagerInterface;
use App\PortalBundle\Repository\MovieRepository;

class MovieManager extends AbstractGenericManager implements MovieManagerInterface, ManagerInterface
{
    /**
     * MovieManager constructor.
     * @param MovieRepository $repository
     */
    public function __construct(MovieRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return 'movieManager';
    }
}
