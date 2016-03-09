<?php

namespace App\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use App\CoreBundle\Traits\Repository\TraitRepository;
use App\CoreBundle\Traits\Repository\TraitSave;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository
{
    use TraitRepository;

    use TraitSave;

    /**
     * @param string $alias
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getBuilder($alias = 'u')
    {
        return $this->createQueryBuilder($alias);
    }

    public function getUserByIdentifierQueryBuilder(QueryBuilder &$qb, $identifier)
    {
        $qb->andWhere(
                $qb->expr()->orX(
                    'u.username = :identifier', 'u.email = :identifier'
                )
            )
            ->setParameter('identifier', $identifier);

        return $this;
    }

    public function getUserByEmailOrUsername($identifier)
    {
        $qb = $this->getBuilder();
        $this->getUserByIdentifierQueryBuilder($qb, $identifier);

        return $qb->getQuery()->getOneOrNullResult();
    }
}