<?php
namespace App\PortalBundle\Entity\Manager\Interfaces;

use App\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use App\PortalBundle\Repository\HashTagRepository;

interface HashTagManagerInterface extends GenericManagerInterface
{
    /**
     * HashTagManager constructor.
     * @param HashTagRepository $repository
     */
    public function __construct(HashTagRepository $repository);
}
