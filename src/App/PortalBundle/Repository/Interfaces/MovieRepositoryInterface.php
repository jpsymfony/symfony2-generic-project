<?php

namespace App\PortalBundle\Repository\Interfaces;
use App\CoreBundle\Repository\GenericRepositoryInterface;

interface MovieRepositoryInterface  extends GenericRepositoryInterface
{
    /**
     * Select all movies in order by title with a max limit
     * 
     * @param integer $max
     */
    public function allOrderByTitle($max);

    public function remove($entity);

    public function findAllByEntity($result = 'object', $MaxResults = NULL, $orderby = '');
}
