<?php

namespace App\PortalBundle\Repository\Interfaces;
use App\CoreBundle\Repository\GenericRepositoryInterface;

interface MovieRepositoryInterface  extends GenericRepositoryInterface
{
    public function remove($entity);

    public function findAllByEntity($result = 'object', $MaxResults = NULL, $orderby = '');
}
