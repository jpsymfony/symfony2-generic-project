<?php
namespace App\CoreBundle\Repository;

interface GenericRepositoryInterface
{
    /**
     * @param $entity
     * @param $persist
     * @param $flush
     * @return mixed
     */
    public function save($entity, $persist = false, $flush = true);

}
