<?php

namespace App\CoreBundle\Traits\Repository\Interfaces;

use Doctrine\ORM\Query;

/**
 * interface Repository
 *
 * @category   Generalisation
 * @package    Repository
 * @subpackage Interface
 */
interface TraitRepositoryInterface
{
    /**
     * @return string
     */
    public function getClassName();

    /**
     * Count all fields existed from the given entity 
     *
     * @param boolean $enabled [0, 1]    
     * 
     * @return string the count of all fields.
     * @access public
     */
    public function count($enabled = null);

    public function remove($entity);
    
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
    public function findAllByEntity($result = "object", $MaxResults = null, $orderby = '');
}
