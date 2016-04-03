<?php
namespace App\PortalBundle\Entity\Manager;

use App\CoreBundle\Entity\Manager\AbstractGenericManager;
use App\PortalBundle\Entity\Manager\Interfaces\HashTagManagerInterface;
use App\PortalBundle\Entity\Manager\Interfaces\ManagerInterface;
use App\PortalBundle\Repository\HashTagRepository;

class HashTagManager extends AbstractGenericManager implements HashTagManagerInterface, ManagerInterface
{
    /**
     * HashTagManager constructor.
     * @param HashTagRepository $repository
     */
    public function __construct(HashTagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return 'hashTagManager';
    }
}
