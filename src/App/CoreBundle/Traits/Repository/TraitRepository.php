<?php

namespace App\CoreBundle\Traits\Repository;

use Doctrine\ORM\Query;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

trait TraitRepository
{
    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->getEntityName();
    }

    /**
     * Count all fields existed from the given entity 
     *
     * @param boolean $enabled [0, 1]    
     * 
     * @return string the count of all fields.
     * @access public
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

    public function remove($entity)
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }
    
   /**
     * Find all translations by an entity.
     *
     * @param string $result = {'array', 'object'}
     * @param int    $MaxResults
     * @param string $orderby 
     * 
     * @return array|object
     * @access public
     */
    public function findAllByEntity($result = "object", $MaxResults = null, $orderby = '')
    {
        $qb = $this->_em->createQueryBuilder()
        ->select('a')
        ->from($this->_entityName, 'a');
        
        if (!empty($orderby)) {
            $qb->orderBy("a.$orderby", 'ASC');
        }
      
        $query = $qb->getQuery();
        
        if (!is_null($MaxResults)) {
            $query->setMaxResults($MaxResults);
        }
        
        return $this->findByQuery($query, $result);
    }

    /**
     * Loads all translations with all translatable fields from the given entity
     *
     * @param Query   $query
     * @param string  $result = {'array', 'object'}
     *
     * @return array|object of result query
     * @access public
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
