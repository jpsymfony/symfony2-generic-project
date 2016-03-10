<?php
namespace App\PortalBundle\Entity\Manager;

use App\PortalBundle\Repository\Interfaces\HashTagRepositoryInterface;

class HashTagManager
{
    /**
     * @var HashTagRepositoryInterface
     */
    protected $hashTagRepository;

    /**
     * HashTagManager constructor.
     * @param HashTagRepositoryInterface $hashTagRepository
     */
    public function __construct(HashTagRepositoryInterface $hashTagRepository)
    {
        $this->hashTagRepository = $hashTagRepository;
    }
    
    public function remove($entity)
    {
        $this->hashTagRepository->remove($entity);
    }

    public function find($hashTag)
    {
        return $this->hashTagRepository->find($hashTag);
    }

    public function save($entity, $persist = false, $flush = true)
    {
        return $this->hashTagRepository->save($entity, $persist, $flush);
    }
}
