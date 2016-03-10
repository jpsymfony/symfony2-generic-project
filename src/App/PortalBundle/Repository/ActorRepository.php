<?php

namespace App\PortalBundle\Repository;

use App\PortalBundle\Repository\Interfaces\ActorRepositoryInterface;
use App\CoreBundle\Traits\Repository\Interfaces\TraitRepositoryInterface;
use App\CoreBundle\Traits\Repository\TraitRepository;
use App\CoreBundle\Traits\Repository\TraitSave;

class ActorRepository extends \Doctrine\ORM\EntityRepository implements ActorRepositoryInterface, TraitRepositoryInterface
{
    use TraitRepository;
    
    use TraitSave;
    
    /**
     * {@inheritdoc}
     */
    public function allOrderByName($max)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('f')
        ->from($this->_entityName, 'f')
        ->orderBy('f.nom', 'DESC')
        ->setMaxResults($max);
        $query = $qb->getQuery();
        
        return $query->getResult();
    }

    public function findbyFirstNameOrLastName($motcle)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('a')
            ->from('AppPortalBundle:Actor', 'a')
            ->where("a.firstName LIKE :motcle OR a.lastName LIKE :motcle")
            ->orderBy('a.lastName', 'ASC')
            ->setParameter('motcle', '%' . $motcle . '%');
        $query = $qb->getQuery();

        return $query->getResult();
    }
}
