<?php

namespace App\CoreBundle\Repository;

use App\CoreBundle\Repository\Interfaces\GenericRepositoryInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

abstract class AbstractGenericRepository extends EntityRepository implements GenericRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBuilder($alias = 'u')
    {
        return $this->createQueryBuilder($alias);
    }

    /**
     * {@inheritdoc}
     */
    public function save($entity, $persist = false, $flush = true)
    {
        if ($persist) {
            $this->_em->persist($entity);
        }

        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function getClassName()
    {
        return $this->getEntityName();
    }

    /**
     * @inheritdoc
     */
    public function getAlias()
    {
        return substr($this->_class->getTableName(), 0, 3);
    }

    /**
     * @inheritdoc
     */
    public function count($enabled = false)
    {
        if ($enabled) {
            return $this->_em
                ->createQuery("SELECT COUNT(c) FROM {$this->_entityName} c WHERE c.enabled = '{$enabled}'")
                ->getSingleScalarResult();
        } else {
            return $this->_em->createQuery("SELECT COUNT(c) FROM {$this->_entityName} c")->getSingleScalarResult();
        }
    }

    /**
     * @inheritdoc
     */
    public function remove($entity)
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }

    /**
     * @inheritdoc
     */
    public function findAllByEntity($result = "object", $maxResults = null, $orderby = '', $dir = 'ASC')
    {
        $qb = $this->createQueryBuilder($this->getAlias());

        if (!empty($orderby)) {
            $qb->orderBy($this->getAlias(). '.' . $orderby, $dir);
        }

        $query = $qb->getQuery();

        if (!is_null($maxResults)) {
            $query->setMaxResults($maxResults);
        }

        return $this->findByQuery($query, $result);
    }

    /**
     * @inheritdoc
     */
    public function findByQuery(Query $query, $result = "array")
    {
        if (!$query) {
            throw new NotFoundResourceException('missing query');
        }
        if ($result == 'array') {
            $entities = $query->getArrayResult();
        } elseif ($result == 'object') {
            $entities = $query->getResult();
        } else {
            throw new \InvalidArgumentException("We haven't set the good option value : array or object !");
        }

        return $entities;
    }

    /**
     * @inheritdoc
     */
    public function paginate(QueryBuilder $qb, $limit = 20, $offset = 0)
    {
        $limit = (int) $limit;
        if ($limit <= 0) {
            throw new \LogicException('$limit must be greater than 0.');
        }

        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        $pager->setMaxPerPage((int) $limit);
        $pager->setCurrentPage(ceil(($offset + 1) / $limit));

        return $pager;
    }
}