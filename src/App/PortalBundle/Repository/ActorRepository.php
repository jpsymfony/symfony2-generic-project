<?php

namespace App\PortalBundle\Repository;

use App\CoreBundle\Repository\AbstractGenericRepository;
use App\PortalBundle\Repository\Interfaces\ActorRepositoryInterface;

class ActorRepository extends AbstractGenericRepository implements ActorRepositoryInterface
{
    public function findByFirstNameOrLastName($motcle)
    {
        $qb = $this->getBuilder('a');
        $qb
            ->where("a.firstName LIKE :motcle OR a.lastName LIKE :motcle")
            ->orderBy('a.lastName', 'ASC')
            ->setParameter('motcle', '%' . $motcle . '%');
        $query = $qb->getQuery();

        return $query->getResult();
    }

    /**
     * @inheritdoc
     */
    public function getActors($limit = 20, $offset = 0)
    {
        $limit = (int) $limit;
        if ($limit <= 0) {
            throw new \LogicException('$limit must be greater than 0.');
        }
        
        $qb = $this->getBuilder('a');

        $qb->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->getArrayResult();

        //return $this->paginate($qb, $limit, $offset);
    }
}
