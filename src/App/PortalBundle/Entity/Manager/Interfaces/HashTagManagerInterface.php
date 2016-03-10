<?php
namespace App\PortalBundle\Entity\Manager\Interfaces;

use App\PortalBundle\Repository\Interfaces\HashTagRepositoryInterface;

interface HashTagManagerInterface
{
    /**
     * HashTagManager constructor.
     * @param HashTagRepositoryInterface $hashTagRepository
     */
    public function __construct(HashTagRepositoryInterface $hashTagRepository);
    
    public function remove($entity);

    public function find($hashTag);

    public function save($entity, $persist = false, $flush = true);
}
