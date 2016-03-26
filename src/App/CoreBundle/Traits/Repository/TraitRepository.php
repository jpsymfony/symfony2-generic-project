<?php

namespace App\CoreBundle\Traits\Repository;

use Doctrine\ORM\Query;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

trait TraitRepository
{
    /**
     * @inheritdoc
     */
    public function getClassName()
    {
        return $this->getEntityName();
    }

    public function getAlias()
    {
        return substr($this->_class->getTableName(), 0, 3);
    }

    /**
     * @inheritdoc
     */
    public function count($enabled = null)
    {
        if (!is_null($enabled)) {
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
    public function findAllByEntity($result = "object", $MaxResults = null, $orderby = '', $dir = 'ASC')
    {
        $qb = $this->createQueryBuilder($this->getAlias());
        
        if (!empty($orderby)) {
            $qb->orderBy($this->getAlias(). '.' . $orderby, $dir);
        }
      
        $query = $qb->getQuery();
        
        if (!is_null($MaxResults)) {
            $query->setMaxResults($MaxResults);
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
}
