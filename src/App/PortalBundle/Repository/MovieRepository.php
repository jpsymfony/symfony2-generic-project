<?php

namespace App\PortalBundle\Repository;

use App\CoreBundle\Form\DataTransformer\DatedmYToDateYmdViewTransformer;
use App\CoreBundle\Repository\AbstractGenericRepository;
use App\PortalBundle\Entity\Movie;
use App\PortalBundle\Repository\Interfaces\MovieRepositoryInterface;

class MovieRepository extends AbstractGenericRepository implements MovieRepositoryInterface
{
    public function getResultFilter($requestVal)
    {
        $qb = $this->getBuilder('f');

        if (!empty($requestVal)) {
            $isReleaseDateFrom = !empty($requestVal['releaseDateFrom']);
            $isReleaseDateTo = !empty($requestVal['releaseDateTo']);

            $viewTranformer = new DatedmYToDateYmdViewTransformer();
            $releaseDateFrom = $viewTranformer->reverseTransform($requestVal['releaseDateFrom']);
            $releaseDateTo = $viewTranformer->reverseTransform($requestVal['releaseDateTo']);

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
                $qb->andWhere('DATE(f.releasedAt) >= :releaseDateFrom')
                    ->setParameter('releaseDateFrom', $releaseDateFrom);
                $qb->andWhere('DATE(f.releasedAt) <= :releaseDateTo')
                    ->setParameter('releaseDateTo', $releaseDateTo);
            } elseif ($isReleaseDateFrom && !$isReleaseDateTo) {
                $qb->andWhere('DATE(f.releasedAt) >= :releaseDateFrom')
                    ->setParameter('releaseDateFrom', $releaseDateFrom);
            } elseif (!$isReleaseDateFrom && $isReleaseDateTo) {
                $qb->andWhere('DATE(f.releasedAt) <= :releaseDateTo')
                    ->setParameter('releaseDateTo', $releaseDateTo);
            }
        }

        $qb->orderBy('f.title', 'ASC');
        $query = $qb->getQuery();

        return $query->getResult();
    }
}
