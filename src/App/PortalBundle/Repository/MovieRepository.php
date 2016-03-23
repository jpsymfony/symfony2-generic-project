<?php

namespace App\PortalBundle\Repository;

use App\PortalBundle\Entity\Movie;
use App\PortalBundle\Repository\Interfaces\MovieRepositoryInterface;
use App\CoreBundle\Traits\Repository\Interfaces\TraitRepositoryInterface;
use App\CoreBundle\Traits\Repository\TraitRepository;
use App\CoreBundle\Traits\Repository\TraitSave;

class MovieRepository extends \Doctrine\ORM\EntityRepository implements MovieRepositoryInterface, TraitRepositoryInterface
{
    use TraitRepository;
    
    use TraitSave;

    public function getResultFilterQueryBuilder($requestVal)
    {
        $qb = $this->createQueryBuilder('f');

        if (!empty($requestVal)) {
            $isReleaseDateFrom = !empty($requestVal['releaseDateFrom']);
            $isReleaseDateTo = !empty($requestVal['releaseDateTo']);

            foreach ($requestVal as $key => $val) {
                if (!empty($requestVal[$key])) {
                    if (in_array($key, Movie::getLikeFieds())) { // title, description
                        $qb->andWhere(sprintf('f.%s LIKE :%s', $key, $key))
                            ->setParameter($key, "%" . $val . "%");
                    }

                    if (in_array($key, Movie::getCollectionFields())) { // hashTags, actors
                        $alias = substr($key, 0, 3);
                        $qb->leftJoin(sprintf('f.%s', $key), $alias);
                        $qb->andWhere(sprintf($alias . '.id IN (:%s)', $key))
                            ->setParameter($key, $val);
                    }

                    if (in_array($key, Movie::getObjectFields())) { // category
                        $qb->andWhere(sprintf('f.%s = :%s', $key, $key))
                            ->setParameter($key, $val);
                    }
                }
            }

            if ($isReleaseDateFrom && $isReleaseDateTo) {
                $qb->andWhere('DATE(f.releaseAt) >= :releaseDateFrom')
                    ->setParameter('releaseDateFrom', $requestVal['releaseDateFrom']);
                $qb->andWhere('DATE(f.releaseAt) <= :releaseDateTo')
                    ->setParameter('releaseDateTo', $requestVal['releaseDateTo']);
            } elseif ($isReleaseDateFrom && !$isReleaseDateTo) {
                $qb->andWhere('DATE(f.releaseAt) >= :releaseDateFrom')
                    ->setParameter('releaseDateFrom', $requestVal['releaseDateFrom']);
            } elseif (!$isReleaseDateFrom && $isReleaseDateTo) {
                $qb->andWhere('DATE(f.releaseAt) <= :releaseDateTo')
                    ->setParameter('releaseDateTo', $requestVal['releaseDateTo']);
            }
        }

        $qb->orderBy('f.title', 'ASC');
        $query = $qb->getQuery();

        return $query->getResult();
    }
}
